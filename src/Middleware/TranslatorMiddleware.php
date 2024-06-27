<?php

namespace Dhtml\FlarumLanguageTranslator\Middleware;

use Dhtml\FlarumLanguageTranslator\Services\TranslatorService;
use Flarum\Foundation\Paths;
use GuzzleHttp\Psr7\Utils;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class TranslatorMiddleware implements MiddlewareInterface
{
    protected $locale;
    protected $translatorService;

    public function __construct()
    {
        $this->translatorService = new TranslatorService();
    }
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);

        //$locale = $this->translatorService->getLocale();

        //$this->logInfo(["locale"=>$locale]);

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
            $content = $this->translatorService->translatePage($content);
        } elseif (strpos($contentType, 'application/json') !== false) {
            $data = json_decode($content, true);
            $data = $this->translatorService->translateApiData($data);
            $content = json_encode($data);
        } elseif (strpos($contentType, 'application/vnd.api+json') !== false) {
            $data = json_decode($content, true);
            $data = $this->translatorService->translateApiData($data);
            $content = json_encode($data);
        }

        return $content;
    }

    public function logInfo($content)
    {
        $paths = resolve(Paths::class);
        $logPath = $paths->storage . (DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . 'language-translator-middleware.log');
        $content = var_export($content, true);
        file_put_contents($logPath, $content, FILE_APPEND);
    }

}
