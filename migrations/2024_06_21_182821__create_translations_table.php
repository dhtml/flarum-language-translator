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
        if ($schema->hasTable('translations')) {
            return;
        }

        $schema->create('translations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('hash', 100);

            $table->string('entity', 100);
            $table->longText('original');
            $table->integer('outdated')->default(0);
            $table->integer('translated')->default(0);
            $table->longText('sub_en')->nullable(); //English
            $table->longText('sub_am')->nullable(); //Amharic
            $table->longText('sub_ar')->nullable(); //Arabic
            $table->longText('sub_bn')->nullable(); //Bengali
            $table->longText('sub_zh')->nullable(); //chinese
            $table->longText('sub_fr')->nullable(); //french
            $table->longText('sub_de')->nullable(); //German
            $table->longText('sub_ha')->nullable(); //Hausa
            $table->longText('sub_hi')->nullable(); //Hindi
            $table->longText('sub_ig')->nullable(); //Igbo
            $table->longText('sub_om')->nullable(); //Oromo
            $table->longText('sub_pt')->nullable(); //Portugese
            $table->longText('sub_ru')->nullable(); //Russian
            $table->longText('sub_sn')->nullable(); //Shona
            $table->longText('sub_es')->nullable(); //Spanish
            $table->longText('sub_sw')->nullable(); //Swahili
            $table->longText('sub_yo')->nullable(); //Yoruba
            $table->longText('sub_zu')->nullable(); //Zulu
            $table->timestamps();
        });
    },
    'down' => function (Builder $schema) {
        //$schema->dropIfExists('translations');
    },
];
