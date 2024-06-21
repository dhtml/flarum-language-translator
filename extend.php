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
use Flarum\Extend;


return [
    (new Extend\Frontend('forum'))
        ->js(__DIR__.'/js/dist/forum.js')
        ->css(__DIR__.'/less/forum.less'),
    (new Extend\Frontend('admin'))
        ->js(__DIR__.'/js/dist/admin.js')
        ->css(__DIR__.'/less/admin.less'),
    new Extend\Locales(__DIR__.'/locale'),


    (new Extend\Routes('api'))
        ->post('/trans', 'language.translator.index', TranslateApiController::class),

    (new Extend\Model(Locale::class)),
];
