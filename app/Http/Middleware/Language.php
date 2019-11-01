<?php
/**
 * Created by PhpStorm.
 * User: Andrew
 * Date: 2018/11/28
 * Time: 16:05
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Cookie;

class Language
{
    /**
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!empty($request->cookie('locale')) && in_array($request->cookie('locale'), Config::get('app.locales'))) {
            App::setLocale($request->cookie('locale'));
        }

        /*if (Session::has('language') AND in_array(Session::get('language'), Config::get('app.locales'))) {
            App::setLocale(Cookie::get('language'));
        }
        else { // This is optional as Laravel will automatically set the fallback language if there is none specified
            App::setLocale(Config::get('app.locale'));
        }*/
        return $next($request);
    }

}
