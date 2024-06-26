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

    protected $settings;
    protected $app;
    protected $cache;

    protected $config;
    protected $google_api_key;
    protected $logger;

    public function __construct(LoggerInterface $logger, SettingsRepositoryInterface $settings, Application $app, Cache $cache)
    {
        $this->settings = $settings;
        $this->app = $app;
        $this->cache = $cache;
        $this->logger = $logger;

        $this->google_api_key = $this->settings->get('dhtml-language-translator.googleKey');
    }

    protected function data(ServerRequestInterface $request, Document $document)
    {
        $code = Arr::get($request->getQueryParams(), 'code');
        $source = Arr::get($request->getParsedBody(), 's');
        $locale = Arr::get($request->getParsedBody(), 'l');

        $t = new TranslatorService($this->settings,$this->app,$this->cache,$this->google_api_key,$this->logger);

        $translate = $t->get($source,$locale);

        return (object) [
            "id" => 1,
            "t" => $translate,
        ];
    }
}
