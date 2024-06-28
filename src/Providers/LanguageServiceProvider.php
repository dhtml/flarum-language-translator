<?php

namespace Dhtml\FlarumLanguageTranslator\Providers;

use Flarum\Foundation\AbstractServiceProvider;
use Flarum\Locale\Translator;

class LanguageServiceProvider extends AbstractServiceProvider
{
    public function register()
    {
        $host = $_SERVER['HTTP_HOST'];

        $parts = explode('.', $host);

        // Check if there are enough parts to have a subdomain
        if (count($parts) >= 3) {
            // The subdomain is the first part
            $subdomain = $parts[0];
        } else {
            // No subdomain present
            $subdomain = '';
        }

        if($subdomain=="") {
            //root domain

        } else {
            $settings = require(__DIR__."/../Settings.php");
            if(isset($settings["locales"][$subdomain])) {
                //set default locale
                $translator = resolve(Translator::class);
                $translator->setLocale($subdomain);
            } else {
                //redirect to root path
                $pageUrl = $this->getFullUrl();
                $rootDomain = str_replace($subdomain.".","",$pageUrl);
                header("Location: $rootDomain");
                exit();
            }
        }

    }

    private function getFullUrl()
    {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';

        // Host
        $host = $_SERVER['HTTP_HOST'];

        // Request URI (including query string if present)
        $requestUri = $_SERVER['REQUEST_URI'];

        // Full URL
        $fullUrl = $protocol . $host . $requestUri;
        return $fullUrl;
    }


}
