<?php

namespace Dhtml\FlarumLanguageTranslator;

use Carbon\Carbon;

use Flarum\Database\AbstractModel;
use Flarum\Database\ScopeVisibilityTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $hash
 * @property string $source
 * @property string $locale
 * @property string $translation
 */
class Locale extends AbstractModel
{
    use ScopeVisibilityTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'locales';

    protected $dates = ['created_at', 'updated_at'];

    protected $fillable = [
        'hash', 'source','locale','translations',
    ];

    /**
     * The text formatter instance.
     *
     * @var \Flarum\Formatter\Formatter
     */
    protected static $formatter;

    /**
     * Create a new page.
     *
     * @return static
     */
    public static function build($hash, $source, $locale, $translation)
    {
        $_locale = new static();

        $_locale->hash = $locale;
        $_locale->source = $source;
        $_locale->locale = $locale;
        $_locale->translation = $translation;
        $_locale->created_at = Carbon::now();
        $_locale->updated_at = Carbon::now();

        return $_locale;
    }

    /**
     * Get the text formatter instance.
     *
     * @return \Flarum\Formatter\Formatter
     */
    public static function getFormatter()
    {
        return static::$formatter;
    }

    /**
     * Set the text formatter instance.
     *
     * @param \Flarum\Formatter\Formatter $formatter
     */
    public static function setFormatter(Formatter $formatter)
    {
        static::$formatter = $formatter;
    }
}
