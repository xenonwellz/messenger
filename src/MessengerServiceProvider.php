<?php

namespace Xenonwellz\Messenger;

use Illuminate\Support\Facades\Gate;
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

        $this->registerPolicies();
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/views', 'messenger');
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations/main');
        if (config('messenger.use_avatar_field')) {
            $this->loadMigrationsFrom(__DIR__ . '/database/migrations/exception');
        }
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations/exception');
        // $this->mergeConfigFrom(__DIR__ . '/config/messenger.php', 'messenger');
    }

    public function register()
    {
        foreach (glob(__DIR__ . '/Helpers/*.php') as $helpersfilename) {
            require_once($helpersfilename);
        }
        $this->app->register(MessengerEventServiceProvider::class);
    }
}
