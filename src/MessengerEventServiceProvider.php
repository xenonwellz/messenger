<?php

namespace Xenonwellz\Messenger;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Xenonwellz\Messenger\Events\UserOnline;

class MessengerEventServiceProvider extends ServiceProvider
{
    protected $listen = [
        UserOnline::class => [],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
