<?php

namespace FraSim\MangoPay;

use FraSim\MangoPay\Console\Commands\InstallCommand;
use FraSim\MangoPay\Console\Commands\SyncCommand;
use Illuminate\Support\ServiceProvider;


class MangoPayServiceProvider extends ServiceProvider
{
    const PATH_FILE_CONFIG = __DIR__ .'/config/mangopay.php';
    const PATH_FILE_ROUTES = __DIR__ .'/routes/routes.php';
    const PATH_DIR_MIGRATIONS = __DIR__ .'/database/migrations';
    const PATH_DIR_FACTORIES = __DIR__ .'/database/factories';
    const PATH_DIR_TRANSLATIONS = __DIR__ .'/resources/lang';
    const PATH_DIR_VIEWS = __DIR__ .'/resources/views';

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(static::PATH_FILE_CONFIG, 'mangopay');
        $this->app->singleton(MangoPayService::class, function($app) {
            return new MangoPayService;
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
                SyncCommand::class
            ]);
        }

        // Routes
        $this->loadRoutesFrom(static::PATH_FILE_ROUTES);
        // Migrations
        $this->loadMigrationsFrom(static::PATH_DIR_MIGRATIONS);
        // Factories
        $this->loadFactoriesFrom(static::PATH_DIR_FACTORIES);
        // Translations
        $this->loadTranslationsFrom(static::PATH_DIR_TRANSLATIONS, 'mangopay');
        // Views
        $this->loadViewsFrom(static::PATH_DIR_VIEWS, 'mangopay');

        // Publish
        $this->publishes([
            static::PATH_FILE_CONFIG => config_path('mangopay.php'),                    // config
            static::PATH_DIR_TRANSLATIONS => resource_path('lang/vendor/mangopay'),     // translations
            static::PATH_DIR_VIEWS => resource_path('views/vendor/mangopay'),           // views
        ], 'mangopay');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [MangoPayService::class];
    }
}
