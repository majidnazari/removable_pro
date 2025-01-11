<?php

namespace App\Providers;

use App\Events\UserRegistered;
use App\Listeners\SendRelationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        UserRegistered::class => [
            SendRelationNotification::class,
        ],
        UpdateClanIdAfterMerge::class => [
            UpdateClanIdAfterMergeListener::class
        ],
    ];

    public function boot()
    {
        parent::boot();
    }
}
