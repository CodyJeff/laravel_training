<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Attendee;
use App\Models\Event;
use App\Policies\AttendeePolicy;
use App\Policies\EventPolicy;

class AuthServiceProvider extends ServiceProvider
{

    // protected $policies = [
    //     Attendee::class => AttendeePolicy::class,
    //     Event::class => EventPolicy::class
    // ];
    /**
     * Register services.
     */
    public function register(): void
    {
        //$this->registerPolicies();
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
