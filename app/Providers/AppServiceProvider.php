<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;
use App\Models\UserMergeRequest;
use App\Policies\UserMergeRequestPolicy;
use Illuminate\Support\Facades\Gate;



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
        //
        Passport::hashClientSecrets();

        Passport::tokensExpireIn(now()->addDays(1));
        Passport::refreshTokensExpireIn(now()->addDays(3));
        Passport::personalAccessTokensExpireIn(now()->addMonths(1));

        //Gate::policy(UserMergeRequest::class, UserMergeRequestPolicy::class);
    }
}
