<?php

namespace Dhtml\FlarumLanguageTranslator;

use Flarum\User\User;
use Illuminate\Database\Eloquent\Builder;

class LocaleRepository
{
    /**
     * @return Builder
     */
    public function query()
    {
        return Locale::query();
    }

    /**
     * @param int $id
     * @param User $actor
     * @return Translate
     */
    public function findOrFail($id, User $actor = null): Locale
    {
        return Locale::findOrFail($id);
    }
}
