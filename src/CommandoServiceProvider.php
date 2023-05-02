<?php

namespace Letecode\Commando;

use Illuminate\Support\ServiceProvider;
use Letecode\Commando\Commands\Files\CreateClass;
use Letecode\Commando\Commands\Files\CreateFile;
use Letecode\Commando\Commands\Files\CreateInterface;
use Letecode\Commando\Commands\Files\CreateLang;
use Letecode\Commando\Commands\Files\CreateRepository;
use Letecode\Commando\Commands\Files\CreateService;
use Letecode\Commando\Commands\Files\CreateTrait;
use Letecode\Commando\Commands\Files\CreateView;

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

        if($this->app->runningInConsole()) {
            $this->commands([
                CreateClass::class,
                CreateInterface::class,
                CreateFile::class,
                CreateLang::class,
                CreateRepository::class,
                CreateService::class,
                CreateTrait::class,
                CreateView::class,
            ]);
        }
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
