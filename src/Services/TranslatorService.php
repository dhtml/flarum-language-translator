<?php

namespace Dhtml\FlarumLanguageTranslator\Services;

use Dhtml\FlarumLanguageTranslator\Locale;
use Google\Cloud\Core\Exception\GoogleException;
use Google\Cloud\Translate\V2\TranslateClient;
use Carbon\Carbon;

use Flarum\Settings\SettingsRepositoryInterface;
use Flarum\Foundation\Application;
use Illuminate\Contracts\Cache\Repository as Cache;

class TranslatorService
{
    protected $settings;
    protected $app;
    protected $cache;

    protected $google_api_key;

    public function __construct(SettingsRepositoryInterface $settings, Application $app, Cache $cache)
    {
        $this->settings = $settings;
        $this->app = $app;
        $this->cache = $cache;
    }

    public function setGoogleAPiKey($google_api_key) {
        $this->google_api_key = $google_api_key;
    }

    /**
     * Translate a string from its detected language to a new locale
     *
     * @param $string
     * @param $locale
     * @return mixed
     */
    public function get($source, $locale = "en")
    {
        $cacheKey = md5($source . $locale);

        // Check if the translation is already cached
        $translation = $this->cache->rememberForever($cacheKey, function () use ($source, $locale, $cacheKey) {
            return $this->translate($source, $locale, $cacheKey);
        });

        return $translation;
    }

    /**
     * Translate a string from its detected language to a new locale
     *
     * @param $string
     * @param $locale
     * @return mixed
     */
    protected function translate($source, $locale , $hash)
    {
        $_locale = Locale::query()->where("source",$source)
            ->where("locale",$locale)
            ->first();
        if($_locale) {
            return $_locale->translation;
        }

        $translation = $source;

        try {
            $translate = new TranslateClient([
                'key' => $this->google_api_key
            ]);

            $tresult = $translate->translate($source, [
                'target' => $locale
            ]);
            $translation = $tresult['text'];

            Locale::firstOrCreate([
                "hash" => $hash,
                "source"=>$source ,
                "locale"=>$locale,
                "translation"=>$translation,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ]);

        } catch (GoogleException $e) {
            print_r($e);
        }

        return $translation;
    }

}
