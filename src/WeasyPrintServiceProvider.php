<?php

namespace SynergiTech\WeasyPrint;

use Illuminate\Support\ServiceProvider;

class WeasyPrintServiceProvider extends ServiceProvider
{
    /**
     * [protected description]
     * @var [type]
     */
    protected $defer = false;

    /**
     * [register description]
     * @return [type] [description]
     */
    public function register()
    {
        $this->app->singleton('synergitech.weasyprint', function () {
            return $this->app->make('SynergiTech\WeasyPrint\WeasyPrint');
        });
    }

    /**
     * [boot description]
     * @return [type] [description]
     */
    public function boot()
    {
        $config = __DIR__ . '/../config/weasyprint.php';

        $this->publishes([
            $config => config_path('weasyprint.php')
        ], 'config');

        $this->mergeConfigFrom($config, 'weasyprint');
    }
}
