<?php

namespace Dhtml\FlarumLanguageTranslator\Api\Controllers;

use Dhtml\FlarumLanguageTranslator\Api\Serializer\LocaleSerializer;

use Dhtml\FlarumLanguageTranslator\Locale;
use Dhtml\FlarumLanguageTranslator\Services\TranslatorService;
use Flarum\Api\Controller\AbstractShowController;

use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

use Flarum\Settings\SettingsRepositoryInterface;
use Flarum\Foundation\Application;
use Illuminate\Contracts\Cache\Repository as Cache;
use Psr\Log\LoggerInterface;

class TranslateApiController extends AbstractShowController
{
    public $serializer = LocaleSerializer::class;

    protected function data(ServerRequestInterface $request, Document $document)
    {
        $source = Arr::get($request->getParsedBody(), 's');
        $locale = Arr::get($request->getParsedBody(), 'l');

        $t = new TranslatorService();

        $translate = $t->get($source,$locale);

        return (object) [
            "id" => 1,
            "t" => $translate,
        ];
    }
}
