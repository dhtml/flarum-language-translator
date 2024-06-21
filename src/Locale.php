<?php

namespace Dhtml\FlarumLanguageTranslator;

use Carbon\Carbon;
use Flarum\Database\AbstractModel;

abstract class Locale extends AbstractModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'locales';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'hash',
        'source',
        'locale',
        'translation'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'hash' => 'string',
        'source' => 'string',
        'locale' => 'string',
        'translation' => 'string',
    ];
}
