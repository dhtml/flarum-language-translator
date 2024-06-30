<?php

/*
 * This file is part of dhtml/flarum-language-translator.
 *
 * Copyright (c) 2024 Anthony Ogundipe.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Dhtml\FlarumLanguageTranslator;

use Dhtml\FlarumLanguageTranslator\Api\Controllers\TranslateApiController;
use Dhtml\FlarumLanguageTranslator\Controllers\GoogleTranslate;
use Dhtml\FlarumLanguageTranslator\Controllers\LanguageCron;
use Dhtml\FlarumLanguageTranslator\Middleware\LocaleMiddleware;
use Dhtml\FlarumLanguageTranslator\Middleware\TranslatorMiddleware;
use Flarum\Extend;

use Flarum\Api\Event\Serializing;

use Flarum\Post\PostValidator;
use Illuminate\Support\Str;

return [


    (new Extend\Frontend('forum'))
        ->js(__DIR__.'/js/dist/forum.js')
        ->css(__DIR__.'/less/forum.less'),
    (new Extend\Frontend('admin'))
        ->js(__DIR__.'/js/dist/admin.js')
        ->css(__DIR__.'/less/admin.less'),
    (new Extend\Settings())
        ->serializeToForum('dhtml-language-translator.googleKey', 'dhtml-language-translator.googleKey'),

    new Extend\Locales(__DIR__.'/locale'),

    (new Extend\Routes('forum'))
        ->get('/GoogleTranslate', 'google.translate', GoogleTranslate::class)
        ->get('/cron-translator', 'cron.translate', LanguageCron::class),

    (new Extend\Routes('api'))
        ->post('/trans', 'language.translator.index', TranslateApiController::class),

    (new Extend\Model(Locale::class)),

    (new Extend\Middleware('forum'))
        ->add(TranslatorMiddleware::class)
        ->add(LocaleMiddleware::class),


    (new Extend\Middleware('api'))
        ->add(TranslatorMiddleware::class),

    (new Extend\ServiceProvider())
        ->register(Providers\LoggerServiceProvider::class),

    (new Extend\ServiceProvider())
        ->register(Providers\LanguageServiceProvider::class),

    (new Extend\Validator(PostValidator::class))
        ->configure(function ($flarumValidator, $validator) {
            $rules = $validator->getRules();

            if (!array_key_exists('content', $rules)) {
                return;
            }

            $rules['content'] = array_map(function(string $rule) {
                if (Str::startsWith($rule, 'max:')) {
                    return 'max:10000';
                }

                return $rule;
            }, $rules['content']);

            $validator->setRules($rules);
        }),

    (new Extend\Console())
        ->command(Console\BatchTranslator::class)
        ->schedule(Console\BatchTranslator::class, Console\BatchTranslatorSchedule::class),

    (new Extend\Console())
        ->command(Console\TranslatorClear::class),

    /*
    (new Extend\Middleware('admin'))
        ->add(TranslatorMiddleware::class),
    */

    /*
    (new Extend\Middleware('forum'))
        ->insertAfter(DispatchRoute::class, TranslatorMiddleware::class),
    */
];
