<?php

namespace Dhtml\FlarumLanguageTranslator\Console;

use Carbon\Carbon;
use Dhtml\FlarumLanguageTranslator\Locale;
use Dhtml\FlarumLanguageTranslator\Services\TranslatorService;
use Dhtml\FlarumLanguageTranslator\Translation;
use Psr\Log\LoggerInterface;

class TranslationEngine
{
    /**
     * @var BatchTranslator
     */
    public $command;
    /**
     * @var mixed|LoggerInterface
     */
    private $logger;

    protected $newTranslation = 1;
    protected $updatedTranslation = 1;
    /**
     * @var TranslatorService
     */
    private $translationService;

    public function __construct(BatchTranslator $command)
    {
        $this->command = $command;
        $this->logger = resolve(LoggerInterface::class);

        $this->translationService = new TranslatorService();
    }

    public function batchTranslate() {
        $this->command->showInfo("Initializing Batch Request");

        //new translations
        $data_1 = Translation::where("translated",0)->limit($this->newTranslation)->get();

        //new translations
        $data_2 = Translation::where("outdated",1)->limit($this->updatedTranslation)->get();

        $rawData = $data_1->merge($data_2);

        foreach ($rawData as $entity) {
            $entity = $this->translationService->translateStoredEntity($entity);
            print_r($entity->toArray());
        }


        $this->command->showInfo("Completed Batch Request");
    }

    public function clearData()
    {
        $this->command->showInfo("Clearing translation and locale data");
        Translation::truncate();
        Locale::truncate();
        $this->command->showInfo("Clearing completed");
    }

}
