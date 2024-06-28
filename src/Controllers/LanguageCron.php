<?php

namespace Dhtml\FlarumLanguageTranslator\Controllers;

use Dhtml\FlarumLanguageTranslator\Console\TranslationEngine;
use Illuminate\Contracts\View\Factory;
use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;

class LanguageCron implements RequestHandlerInterface
{
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
        $trans = new TranslationEngine();
        ob_start();
        $trans->batchTranslate();
        $content = ob_get_contents();
        @ob_clean();

        $content = nl2br($content);

        $html = 'Translator Cron Completed at ' . date("y-m-d h:i:s");
        $html .= "<br>Result:<br>$content";

        return new HtmlResponse($html);
    }
}
