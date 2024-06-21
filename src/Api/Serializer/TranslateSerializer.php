<?php

namespace Dhtml\FlarumLanguageTranslator\Api\Serializer;

use Flarum\Api\Serializer\AbstractSerializer;
use Dhtml\FlarumLanguageTranslator\Translate;
use InvalidArgumentException;

class TranslateSerializer extends AbstractSerializer
{
    /**
     * {@inheritdoc}
     */
    protected $type = 'translates';

    /**
     * {@inheritdoc}
     *
     * @param Translate $model
     * @throws InvalidArgumentException
     */
    protected function getDefaultAttributes($model)
    {
        if (! ($model instanceof Translate)) {
            throw new InvalidArgumentException(
                get_class($this).' can only serialize instances of '.Translate::class
            );
        }

        // See https://docs.flarum.org/extend/api.html#serializers for more information.

        return [
            // ...
        ];
    }
}
