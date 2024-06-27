<?php

namespace Dhtml\FlarumLanguageTranslator\Console;

use Flarum\Console\AbstractCommand;
use Flarum\Foundation\Paths;
use Psr\Log\LoggerInterface;

class BatchTranslator extends AbstractCommand
{

    /**
     * @var mixed|LoggerInterface
     */
    private $logger;

    public function __construct(?string $name = null)
    {
        parent::__construct($name);

        $this->logger = resolve(LoggerInterface::class);
    }

    protected function configure()
    {
        $this
            ->setName('translate')
            ->setDescription('Translation in batches');
    }

    protected function fire()
    {
        $trans = new TranslationEngine($this);
        $trans->batchTranslate();
        //$this->logInfo("Batch Translator Mode");
        //$this->info('Hello from Batch Translator!');
    }

    public function logInfo($content)
    {
        $paths = resolve(Paths::class);
        $logPath = $paths->storage.(DIRECTORY_SEPARATOR.'logs'.DIRECTORY_SEPARATOR.'language-translator.log');
        $content = var_export($content, true);
        file_put_contents($logPath, $content, FILE_APPEND);
    }

    public function showInfo($content)
    {
        $this->info($content);
    }

}
