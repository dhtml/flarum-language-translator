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
        $html = "<h1>Hello World</h1>";
        return new HtmlResponse($html);
    }
}
