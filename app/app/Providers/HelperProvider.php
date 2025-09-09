<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class HelperProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        require_once app_path() . '/Helpers/Url/WebUrl.php';
        require_once app_path() . '/Helpers/String/StringHelper.php';
        require_once app_path() . '/Helpers/Date/DateHelper.php';
        require_once app_path() . '/Helpers/Array/ArrayHelper.php';
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
