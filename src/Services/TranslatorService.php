<?php

namespace Dhtml\FlarumLanguageTranslator\Services;

use Carbon\Carbon;
use Dhtml\FlarumLanguageTranslator\Locale;
use Dhtml\FlarumLanguageTranslator\Translation;
use Flarum\Foundation\Application;
use Flarum\Foundation\Paths;
use Flarum\Locale\Translator;
use Flarum\Settings\SettingsRepositoryInterface;
use Google\Cloud\Core\Exception\GoogleException;
use Google\Cloud\Translate\V2\TranslateClient;
use Illuminate\Contracts\Cache\Repository as Cache;

class TranslatorService
{
    protected $settings;
    protected $app;
    protected $cache;

    protected $locale = "en";

    protected $supportedLocales = [
        "en",
        "am",
        "ar",
        "bn",
        "zh",
        "fr",
        "de",
        "ha",
        "hi",
        "ig",
        "om",
        "pt",
        "ru",
        "sn",
        "es",
        "sw",
        "yo",
        "zu"
    ];

    protected $google_api_key;
    /**
     * @var Translator|mixed
     */
    private $translator;

    public function __construct()
    {
        $this->settings = resolve(SettingsRepositoryInterface::class);
        $this->app = resolve(Application::class);
        $this->cache = resolve(Cache::class);
        $this->google_api_key = $this->settings->get('dhtml-language-translator.googleKey');

        $this->translator = resolve(Translator::class);
    }

    public function getLocale() {
        return $this->translator->getLocale();
    }

    /**
     * Translate a string from its detected language to a new locale
     *
     * @param $string
     * @param $locale
     * @return mixed
     */
    public function get($source, $locale = "en", $cache = true)
    {
        $cacheKey = md5($source . $locale);

        // Check if the translation is already cached

        if($cache) {
            $translation = $this->cache->rememberForever($cacheKey, function () use ($source, $locale, $cacheKey) {
                return $this->translate($source, $locale, $cacheKey);
            });
        } else {
            $translation = $this->translate($source, $locale, $cacheKey);
        }

        return $translation;
    }



    /**
     * Translate a string from its detected language to a new locale
     *
     * @param $string
     * @param $locale
     * @return mixed
     */
    protected function translate($source, $locale, $hash)
    {
        $_locale = Locale::query()->where("source", $source)
            ->where("locale", $locale)
            ->first();
        if ($_locale) {
            return $_locale->translation;
        }

        $translation = $this->translationDriver($source, $locale);


        Locale::firstOrCreate([
            "hash" => $hash,
            "source" => $source,
            "locale" => $locale,
            "translation" => $translation,
            "created_at" => Carbon::now(),
            "updated_at" => Carbon::now(),
        ]);

        return $translation;
    }

    protected function translationDriver($html, $locale)
    {
        $maxLength = 5000;
        $translatedHtml = '';

        // Split the HTML content into chunks
        $chunks = str_split($html, $maxLength);

        // Translate each chunk and combine the results
        foreach ($chunks as $chunk) {
            $response = $this->translateHTML($chunk, $locale);
            if (!$response['success']) {
                return $html; //when there is a failure, return the original and log it
            }
            $translatedHtml .= $response['content'];
        }

        return $translatedHtml;
    }

    protected function translateHTML(string $html, $locale)
    {
        $response = [
            "success" => false,
            "content" => "",
            "error" => null,
        ];

        try {
            $translate = new TranslateClient([
                'key' => $this->google_api_key
            ]);

            $tresult = $translate->translate($html, [
                'target' => $locale
            ]);

            $response['success'] = true;
            $response['content'] = $tresult['text'];

        } catch (GoogleException $e) {
            $response['error'] = $e->getMessage();
            $this->logInfo("Google API Failed: " . $e->getMessage());
            $result = $html;
        }

        return $response;
    }

    public function logInfo($content)
    {
        $paths = resolve(Paths::class);
        $logPath = $paths->storage . (DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . 'language-translator.log');
        $content = var_export($content, true);
        file_put_contents($logPath, $content, FILE_APPEND);
    }

    public function translatePage($content)
    {
        return $content;
    }

    public function translateApiData($data)
    {
        $this->searchAndTranslateAttributes($data);
        return $data;
    }

    public function searchAndTranslateAttributes(&$array)
    {
        foreach ($array as &$item) {
            if (is_array($item)) {
                if (isset($item['type']) && isset($item['attributes'])) {

                    switch ($item['type']) {
                        case "page":
                            $tdata = $this->translateEntity($item,
                                [
                                    "title" => $item['attributes']['title'],
                                    "content" => $item['attributes']['content'],
                                ]);
                            $item['attributes']['title'] = $tdata['title'];
                            $item['attributes']['content'] = $tdata['content'];
                            break;
                        case "discussions":
                            $tdata = $this->translateEntity($item,
                                [
                                    "title" => $item['attributes']['title'],
                                ]);
                            $item['attributes']['title'] = $tdata['title'];
                            break;
                        case "tags":
                            $tdata = $this->translateEntity($item,
                                [
                                    "name" => $item['attributes']['name'],
                                    "description" => $item['attributes']['description'],
                                ]);
                            $item['attributes']['name'] = $tdata['name'];
                            $item['attributes']['description'] = $tdata['description'];
                            break;

                        case "posts":
                            if (!empty($item['attributes']['contentHtml']) && is_string($item['attributes']['contentHtml'])) {
                                $tdata = $this->translateEntity($item,
                                    [
                                        "contentHtml" => $item['attributes']['contentHtml'],
                                    ]);

                                $item['attributes']['contentHtml'] = $tdata['contentHtml'];
                            }
                            break;

                        case "badges":
                            $tdata = $this->translateEntity($item,
                                [
                                    "name" => $item['attributes']['name'],
                                ]);
                            $item['attributes']['name'] = $tdata['name'];
                            break;

                        case "users":
                        case "userBadges":
                            //no action
                            break;
                        default:
                            $this->logInfo($item);
                    }
                } else {
                    $this->searchAndTranslateAttributes($item);
                }
            }
        }
    }

    /**
     * it returns the same structure it receives in Array
     * @param $item
     * @param $data
     * @return void
     */
    protected function translateEntity($item, $data)
    {
        $entity = $item['type'] . '-' . $item['id'];
        $outdated = 0;

        $original = json_encode($data);
        $hash = md5($original);

        $translation = Translation::where('entity', $entity)->first();
        if ($translation) {
            //translation already exist
            if ($hash != $translation->hash) {
                //original source as changed
                $translation->outdated = 1;
                $translation->hash = $hash;
                $translation->original = $original;
                $translation->save();
            }
        } else {
            //create translation
            $translation = Translation::firstOrCreate([
                "entity" => $entity,
                "hash" => $hash,
                "original" => $original,
                "outdated" => 0,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ]);
        }

        $locale = $this->getLocale();

        $transData = $translation->toArray();
        $result = $transData["sub_" . $locale] ?? null;

        if (strlen($result) < 3) {
            $result = null;
        }

        if (!$result) {
            $result = $transData['original'];
        }

        return (array)json_decode($result);
    }

    public function translateStoredEntity($entity)
    {
        $original = (array) json_decode($entity->original);
        //$this->supportedLocales
        //print_r($original);

        foreach($this->supportedLocales as $locale) {
            $data = $original;
            foreach ($data as $key => &$value) {
                    $value = $this->get($value, $locale,false);
            }
            $processed = json_encode($data);
            $property = "sub_" . $locale;
            $entity->{$property} = $processed;
        }

        //update the flag
        $entity->translated = 1;
        $entity->outdated = 0;
        $entity->save();

        return $entity;
    }

    protected function t($content)
    {
        return $this->get($content, $this->getLocale());
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

}
