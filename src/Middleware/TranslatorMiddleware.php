<?php

namespace Dhtml\FlarumLanguageTranslator\Middleware;

use GuzzleHttp\Psr7\Utils;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Flarum\Locale\Translator;
use Psr\Log\LoggerInterface;

class CheckHtmlMiddleware implements MiddlewareInterface
{
    protected $translator;
    protected $locale;

    public function __construct(Translator $translator, LoggerInterface $logger)
    {
        $this->translator = $translator;
        $this->logger = $logger;
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

        $this->logger->info('Current locale: ' . $this->locale);

        // Get the current Flarum locale
        //$locale = resolve('flarum.locale');

        //echo $locale;

        if (strpos($contentType, 'text/html') !== false) {
            // Example modification for HTML: Adding text before </body>
            $search = '</body>';
            $replace = '<p>Added Text</p></body>';
            $content = str_replace($search, $replace, $content);

            $content = str_replace("Translator","Radio",$content);
            $content = str_replace("Introduce","Personify",$content);
            $content = str_replace("major","minor",$content);
            $content = str_replace("Mission","Division",$content);

        } elseif (strpos($contentType, 'application/json') !== false) {
            $data = json_decode($content, true);

            //$this->dump($data['data']);

            $data = $this->replaceInJson($data, 'major', 'minor');
            $content = json_encode($data);
        } elseif (strpos($contentType, 'application/vnd.api+json') !== false) {
            $data = json_decode($content, true);

            //$this->dump($data['data']);

            $data = $this->replaceInJson($data, 'major', 'minor');
            $content = json_encode($data);
        }

        return $content;
    }

    protected function replaceInJson($data, $search, $replace)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->replaceInJson($value, $search, $replace);
            }
        } elseif (is_string($data)) {
            $data = str_replace($search, $replace, $data);
        }
        return $data;
    }

    protected function dump($data)
    {
       echo json_encode($data,JSON_PRETTY_PRINT);
       die();
    }

}
