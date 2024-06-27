<?php

namespace Dhtml\FlarumLanguageTranslator\Console;

use Flarum\Settings\SettingsRepositoryInterface;
use Illuminate\Console\Scheduling\Event;
use Flarum\Foundation\Paths;

class BatchTranslatorSchedule
{

    public function __invoke(Event $event)
    {
        $settings = resolve(SettingsRepositoryInterface::class);

        $event
            ->everyMinute()
            ->withoutOverlapping();

            $event->onOneServer();

            $paths = resolve(Paths::class);
            $event->appendOutputTo($paths->storage.(DIRECTORY_SEPARATOR.'logs'.DIRECTORY_SEPARATOR.'language-translator-event.log'));
    }
}
