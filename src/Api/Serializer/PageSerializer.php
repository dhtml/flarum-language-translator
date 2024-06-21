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
use Dhtml\FlarumLanguageTranslator\Locale;

class LocaleSerializer extends AbstractSerializer
{
    /**
     * {@inheritdoc}
     */
    protected $type = 'locales';

    /**
     * @param Locale $locale
     *
     * @return array
     */
    protected function getDefaultAttributes($locale)
    {
        $attributes = [
            'id'          => $locale->id,
            'hash'       => $locale->hash,
            'source'        => $locale->source,
            'locale'        => $locale->locale,
            'translation'    => $locale->translation,
        ];

        return $attributes;
    }
}
