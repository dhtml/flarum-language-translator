<?php

namespace Dhtml\FlarumLanguageTranslator\Console;

use Flarum\Console\AbstractCommand;
use Flarum\Foundation\Paths;

class BatchTranslator extends AbstractCommand
{

    protected function configure()
    {
        $this
            ->setName('translate')
            ->setDescription('Translation in batches');
    }

    protected function fire()
    {
        $this->logInfo("Batch Translator Mode");
        $this->info('Hello from Batch Translator!');
    }

    public function logInfo($content)
    {
        $paths = resolve(Paths::class);
        $logPath = $paths->storage.(DIRECTORY_SEPARATOR.'logs'.DIRECTORY_SEPARATOR.'language-translator.log');
        $content = var_export($content, true);
        file_put_contents($logPath, $content, FILE_APPEND);
    }

}
