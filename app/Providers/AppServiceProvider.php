<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Team;
use App\Policies\TeamPolicy;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        Team::class => TeamPolicy::class,
    ];

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
