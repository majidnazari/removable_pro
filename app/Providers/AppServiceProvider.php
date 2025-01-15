<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;
use App\Models\UserMergeRequest;
use App\Policies\UserMergeRequestPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use App\Rules\Share\CheckPersonOfEachUser;

use Log;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // if ($this->app->environment('local')) {
        //     // Register local-only services
        //    Log::info("reister some of services in local");
        // }

        // if ($this->app->environment('production')) {
        //     // Register local-only services
        //    Log::info("reister some of services in production");
        // }  
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

        // Register custom validation rules
        Validator::extend('CheckPersonOfEachUser', function ($attribute, $value, $parameters, $validator) {
            // Assuming person_id is passed as the first parameter
            $personId = $parameters[0];

            // Create an instance of the custom rule and validate
            $rule = new CheckPersonOfEachUser($personId);
            return $rule->passes($attribute, $value);
        });

        // if ($this->app->environment('production')) {
        //     Log::info("the production just running!.");
        // }
        // if ($this->app->environment('local')) {
        //     Log::info("the local just running!.");
        // }

        //Gate::policy(UserMergeRequest::class, UserMergeRequestPolicy::class);
    }
}
