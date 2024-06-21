<?php

namespace Dhtml\FlarumLanguageTranslator\Api\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Flarum\Api\Controller\AbstractSerializeController;
use Flarum\Http\RequestUtil;

class TranslateApiController extends AbstractSerializeController
{
    protected function data(ServerRequestInterface $request, Document $document)
    {
        return [
            'message' => 'Hello, this is your custom API response!',
            'user' => "Tony"
        ];
    }
}
