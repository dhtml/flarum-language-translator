<?php

/*
 * This file is part of fof/pages.
 *
 * Copyright (c) FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;

return [
    'up' => function (Builder $schema) {
        if ($schema->hasTable('locales')) {
            return;
        }

        $schema->create('locales', function (Blueprint $table) {
            $table->increments('id');
            $table->string('hash', 100);
            $table->longText('source');
            $table->string('locale', 2);
            $table->longText('translation');
            $table->timestamps();
        });
    },
    'down' => function (Builder $schema) {
        //$schema->dropIfExists('locales');
    },
];
