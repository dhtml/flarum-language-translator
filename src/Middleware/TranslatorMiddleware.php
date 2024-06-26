<?php

namespace Dhtml\FlarumLanguageTranslator\Middleware;

use Dhtml\FlarumLanguageTranslator\Services\TranslatorService;
use GuzzleHttp\Psr7\Utils;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Flarum\Locale\Translator;
use Psr\Log\LoggerInterface;

use Flarum\Settings\SettingsRepositoryInterface;
use Flarum\Foundation\Application;
use Illuminate\Contracts\Cache\Repository as Cache;

class TranslatorMiddleware implements MiddlewareInterface
{
    protected $translator;
    protected $locale;

    protected $settings;
    protected $app;
    protected $cache;
    protected $config;
    protected $google_api_key;
    protected $translatorService;
    protected $logger;

    public function __construct(Translator $translator, LoggerInterface $logger,SettingsRepositoryInterface $settings, Application $app, Cache $cache)
    {
        $this->translator = $translator;
        $this->logger = $logger;

        $this->settings = $settings;
        $this->app = $app;
        $this->cache = $cache;
        $this->google_api_key = $this->settings->get('dhtml-language-translator.googleKey');

        $this->translatorService = new TranslatorService($this->settings,$this->app,$this->cache,$this->google_api_key,$this->logger);
    }
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Get the current Flarum locale
        $this->locale = $this->translator->getLocale();

        $response = $handler->handle($request);

        // Process HTML and JSON responses
        $contentType = $response->getHeaderLine('Content-Type');

        if (strpos($contentType, 'text/html') !== false || strpos($contentType, 'application/json') !== false || strpos($contentType, 'application/vnd.api+json') !== false) {
            $body = (string) $response->getBody();
            $body = $this->modifyContent($body, $contentType);

            // Convert modified content to StreamInterface
            $stream = Utils::streamFor($body);

            // Create a new response with the modified body
            $response = $response->withBody($stream);
        }

        return $response;
    }
    protected function modifyContent(string $content, string $contentType): string
    {

        //$this->logger->info('Current locale: ' . $this->locale);

        if (strpos($contentType, 'text/html') !== false) {
            $content = $this->translatorService->translatePage($content,$this->locale);
        } elseif (strpos($contentType, 'application/json') !== false) {
            $data = json_decode($content, true);
            $data = $this->translatorService->translateApiData($data,$this->locale);
            $content = json_encode($data);
        } elseif (strpos($contentType, 'application/vnd.api+json') !== false) {
            $data = json_decode($content, true);
            $data = $this->translatorService->translateApiData($data,$this->locale);
            $content = json_encode($data);
        }

        return $content;
    }
}
