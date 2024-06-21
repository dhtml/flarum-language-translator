<?php

namespace Dhtml\FlarumLanguageTranslator\Middleware;

use GuzzleHttp\Psr7\Utils;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CheckHtmlMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);

        // Only process HTML responses
        if (strpos($response->getHeaderLine('Content-Type'), 'text/html') !== false) {
            $body = (string) $response->getBody();
            $body = $this->modifyHtml($body);


            // Convert modified HTML to StreamInterface
            $stream = Utils::streamFor($body);

            // Create a new response with the modified body
            $response = $response->withBody($stream);
        }

        return $response;
    }

    protected function modifyHtml(string $html): string
    {
        // Ensure to modify the HTML correctly
        // Example: Adding a specific text before </body>
        $search = '</body>';
        $replace = '<p>Added Text</p></body>';
        $html = str_replace($search, $replace, $html);

        $html = str_replace("Translator","Radio",$html);
        $html = str_replace("Introduce","Personify",$html);
        $html = str_replace("major","minor",$html);
        $html = str_replace("Mission","Division",$html);

        return $html;
    }

}
