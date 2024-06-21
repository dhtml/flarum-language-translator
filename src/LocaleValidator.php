<?php

namespace Dhtml\FlarumLanguageTranslator;

use Flarum\Foundation\AbstractValidator;

class LocaleValidator extends AbstractValidator
{
    /**
     * {@inheritdoc}
     */
    protected $rules = [
        'hash' => [
            'required',
            'max:200',
        ],
        'source' => [
            'required',
        ],
        'locale' => [
            'required',
        ],
        'translation' => [
            'required',
        ],
    ];
}
