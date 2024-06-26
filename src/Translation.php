<?php

namespace Dhtml\FlarumLanguageTranslator;

use Carbon\Carbon;
use Flarum\Database\AbstractModel;
use Flarum\Database\ScopeVisibilityTrait;

/**
 * @property string $hash
 * @property string $source
 * @property string $locale
 * @property string $translation
 */
class Translation extends AbstractModel
{
    use ScopeVisibilityTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'translations';

    //mass assign all except
    protected $guarded = ['id'];

    protected $dates = ['created_at', 'updated_at'];

}
