<?php

namespace Letecode\Commando;

use Illuminate\Support\ServiceProvider;

class CommandoServiceProvider extends ServiceProvider
{
    /**
     * Commando Config file path
     */
    const CONFIG_PATH = __DIR__ . '/../config/commando.php';

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            self::CONFIG_PATH => config_path('commando.php'),
        ], 'config');
    }

     /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            self::CONFIG_PATH,
            'commando'
        );
    }
}
