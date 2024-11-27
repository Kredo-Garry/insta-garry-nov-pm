<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();

        Gate::define('admin', function($user){
            /**
             * Check if the role_id of the user who is logged-in is equal to the of the admin user
             */
            return $user->role_id === User::ADMIN_ROLE_ID; //return if the condition is TRUE
        });

        if (config('app.env' === 'production')) {
            \URL::forceScheme(config('https'));
        }
    }
}
