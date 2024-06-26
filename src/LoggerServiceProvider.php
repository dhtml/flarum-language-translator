<?php

namespace Dhtml\FlarumLanguageTranslator;

use Flarum\Foundation\AbstractServiceProvider;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class LoggerServiceProvider extends AbstractServiceProvider
{
    public function register()
    {
        $this->container->singleton('dhtml-language-translator-logger', function () {
            $logger = new Logger('dhtml-language-translator');
            $logger->pushHandler(new StreamHandler(storage_path('logs/dhtml-language-translator.log'), Logger::DEBUG));

            return $logger;
        });
    }
}
