<?php

namespace App\Providers;

use App\Http\Controllers\WebhookController;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Cache\SymfonyCache;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\BotMan\Storages\Drivers\FileStorage;
use BotTemplateFramework\Distinct\Telegram\TelegramDriverExtended;
use BotTemplateFramework\TemplateEngine;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        DriverManager::loadDriver(TelegramDriverExtended::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('botman', function($app) {
            $config = TemplateEngine::getConfig(WebhookController::getScenario());
            $cache = new SymfonyCache(new FilesystemAdapter('cache', 120, storage_path('app')));
            $bot = BotManFactory::create($config, $cache, null, new FileStorage(storage_path('app')));
            return $bot;
        });
        $this->app->singleton('engine', function($app) {
            return new TemplateEngine(WebhookController::getScenario(), resolve('botman'));
        });
    }
}
