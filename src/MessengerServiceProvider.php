<?php

namespace Xenonwellz\Messenger;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class MessengerServiceProvider extends ServiceProvider

{
    protected $policies = [
        \App\Models\User::class => Policies\MessagePolicy::class
    ];

    public function registerPolicies()
    {
        foreach ($this->policies as $key => $value) {
            Gate::policy($key, $value);
        }
    }
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/messenger.php', 'messenger');
        $this->registerPolicies();
        $this->loadRoutes();
        $this->loadViewsFrom(__DIR__ . '/views', 'messenger');
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations/main');
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations/exception');
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations/exception');
        $this->publishes([
            __DIR__ . '/config/messenger.php' => config_path('messenger.php')
        ], 'messenger-config');

        // Migrations
        $this->publishes([
            __DIR__ . '/database/migrations/' => database_path('migrations')
        ], 'messenger-migrations');

        // Models
        $isV8 = explode('.', app()->version())[0] >= 8;
        $this->publishes([
            __DIR__ . '/Models' => app_path($isV8 ? 'Models' : '')
        ], 'messenger-models');

        // Controllers
        $this->publishes([
            __DIR__ . '/Http/Controllers' => app_path('Http/Controllers/vendor/messenger')
        ], 'messenger-controllers');

        // Views
        $this->publishes([
            __DIR__ . '/views' => resource_path('views/vendor/messenger')
        ], 'messenger-views');

        // Assets
        $this->publishes([
            // CSS
            __DIR__ . '/assets/css' => public_path('css/messenger'),
            // JavaScript
            __DIR__ . '/assets/js' => public_path('js/messenger'),
        ], 'messenger-assets');
    }

    public function register()
    {
        foreach (glob(__DIR__ . '/Helpers/*.php') as $helpersfilename) {
            require_once($helpersfilename);
        }
        $this->app->register(MessengerEventServiceProvider::class);
    }

    protected function loadRoutes()
    {
        Route::group($this->routeConfigure(), function () {
            $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        });
        $this->loadRoutesFrom(__DIR__ . '/routes/channels.php');
    }

    private function routeConfigure()
    {
        return [
            'prefix' => config('messenger.route_prefix'),
            'middleware' => ['web', 'auth']
        ];
    }
}
