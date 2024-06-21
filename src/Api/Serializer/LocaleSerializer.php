<?php

/*
 * This file is part of fof/pages.
 *
 * Copyright (c) FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dhtml\FlarumLanguageTranslator\Api\Serializer;

use Flarum\Api\Serializer\AbstractSerializer;

class LocaleSerializer extends AbstractSerializer
{
    /**
     * {@inheritdoc}
     */
    protected $type = 'lc';

    /**
     * @param Locale $locale
     *
     * @return array
     */
    protected function getDefaultAttributes($locale)
    {
        $attributes = [
            //'id'  => $locale->id,
            't' => $locale->t,
        ];
        return $attributes;
    }
}
