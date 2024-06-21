<?php

namespace Dhtml\FlarumLanguageTranslator\Api\Controllers;

use Dhtml\FlarumLanguageTranslator\Api\Serializer\LocaleSerializer;

use Dhtml\FlarumLanguageTranslator\Locale;
use Dhtml\FlarumLanguageTranslator\Services\TranslatorService;
use Flarum\Api\Controller\AbstractCreateController;

use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

use Psr\Http\Message\ResponseInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Flarum\Http\RequestUtil;
use Illuminate\Contracts\Config\Repository as ConfigRepository;

class TranslateApiController extends AbstractCreateController
{
    public $serializer = LocaleSerializer::class;

    protected $config;
    protected $google_api_key;

    public function __construct()
    {
        $baseDir = dirname(dirname(dirname(dirname(dirname(dirname(__DIR__))))));

        $this->config = require $baseDir . '/config.php';
        $this->google_api_key = $this->config['google_api_key'];

        //echo $this->google_api_key;
    }

    protected function data(ServerRequestInterface $request, Document $document)
    {

        $code = Arr::get($request->getQueryParams(), 'code');
        $source = Arr::get($request->getParsedBody(), 's');
        $locale = Arr::get($request->getParsedBody(), 'l');

        $t = new TranslatorService($this->google_api_key);

        $translate = $t->get($source,$locale);

        return (object) [
            "id" => 1,
            "t" => $translate,
        ];
    }
}
