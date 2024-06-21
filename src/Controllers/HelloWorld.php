<?php

namespace Dhtml\FlarumLanguageTranslator\Controllers;

use Illuminate\Contracts\View\Factory;
use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;

class HelloWorld implements RequestHandlerInterface
{
    protected $viewRenderer;

    /**
     * @var Factory
     */
    protected $view;

    public function __construct(Factory $view)
    {
        $this->view = $view;
    }

    public function handle(Request $request): ResponseInterface
    {
        $title = 'Hello, world!';
        $content = 'This is the content of the page.';

        return new HtmlResponse($html);
    }
}
