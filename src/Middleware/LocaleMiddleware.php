<?php

namespace Dhtml\FlarumLanguageTranslator\Middleware;

use Flarum\Http\RequestUtil;
use Flarum\Locale\LocaleManager;
use Illuminate\Support\Arr;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as Handler;

class LocaleMiddleware implements Middleware
{
    /**
     * @var LocaleManager
     */
    protected $locales;

    /**
     * @param LocaleManager $locales
     */
    public function __construct(LocaleManager $locales)
    {
        $this->locales = $locales;
    }

    public function process(Request $request, Handler $handler): Response
    {
        if(!isset($_SERVER['HTTP_HOST'])) {return $handler->handle($request);}

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

        $locale = $subdomain;


        $actor = RequestUtil::getActor($request);

        if ($actor->exists) {
            //for currently logged in users, must update their locale settings first
            $_locale = $actor->getPreference('locale');
            if($_locale!=$locale) {
                $actor->setPreference('locale', $locale);
                $actor->save();
            }
        }


        $this->locales->setLocale($locale);
        $request = $request->withAttribute('locale', $this->locales->getLocale());

        return $handler->handle($request);
    }
}
