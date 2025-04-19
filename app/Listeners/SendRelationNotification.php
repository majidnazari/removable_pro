<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use App\GraphQL\Enums\NotifStatus;
use App\Models\Person;
use App\Models\Notif;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use Log;


class SendRelationNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserRegistered $event): void
    {
        $user = $event->user;

        $country_code = $user->country_code;    // e.g. "0098"
        $raw_mobile = $user->mobile;            // e.g. "00989372120890"

        // Remove the country code prefix from the raw_mobile
        // This assumes raw_mobile starts with the country_code
        $local_mobile = substr($raw_mobile, strlen($country_code));


        // Check if user's mobile exists in person table
        $persons = Person::where('mobile', $local_mobile)->where('country_code', $country_code)->get();

        foreach ($persons as $person) {
            Notif::create([
                'creator_id' => $user->id,
                'notifiable_id' => $person->id, // The ID of the person who receives the notification
                'notifiable_type' => get_class($person), // The model name of the notifiable entity (e.g., 'App\Models\User')        
                'message' => "the mobile number { $user->mobile}  is registered",
                'notif_status' => NotifStatus::NotRead
            ]);
        }
    }
}
