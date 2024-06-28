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

        $settings = require(__DIR__ . "/../Settings.php");

        if ($subdomain == "") {
            //root domain hit
            $subdomain = $this->getUserPreferredLanguage();

            if (!isset($settings["locales"][$subdomain])) {
                $subdomain = "en"; //default
            }
            //redirect to preferred domain
            $pageUrl = $this->getFullUrl();
            $rootDomain = str_replace( "://", "://$subdomain.", $pageUrl);
            header("Location: $rootDomain");
            exit();
        } else {
            if (isset($settings["locales"][$subdomain])) {
                //set default locale
                $translator = resolve(Translator::class);
                $translator->setLocale($subdomain);
            } else {
                //redirect to root path
                $pageUrl = $this->getFullUrl();
                $rootDomain = str_replace($subdomain . ".", "", $pageUrl);
                header("Location: $rootDomain");
                exit();
            }
        }

    }

    function getUserPreferredLanguage()
    {
        if (!isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            return 'en'; // Default to English if header not present
        }

        $languages = $_SERVER['HTTP_ACCEPT_LANGUAGE'];

        // Parse languages from the header (first language code)
        $preferredLanguage = explode(',', $languages)[0];
        $preferredLanguage = strtolower(substr($preferredLanguage, 0, 2)); // Extract first two characters (language code)

        // Optionally, you can validate $preferredLanguage against a list of supported languages

        return $preferredLanguage;
    }

// Function to parse Accept-Language header and extract language codes

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
