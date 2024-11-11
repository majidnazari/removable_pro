<?php

namespace App\GraphQL\Mutations\User;

use App\Models\IpTracking;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Joselfonseca\LighthouseGraphQLPassport\GraphQL\Mutations\BaseAuthResolver;
use Carbon\Carbon;
use App\Models\User;
use App\Models\LoginAttempt;
use App\GraphQL\Enums\Status;


use GraphQL\Error\Error;

use Log;

class RegisterMobile
{
    public $today ;

    const MAX_USER_ATTEMPTS = 3;
    const MAX_IP_ATTEMPTS = 2;
    const BLOCK_DURATION = 1440; // in minutes (1 day)

    public function __construct(){
         $this->today = Carbon::now()->format("Y-m-d");
    }

    public $error = [
        "Code" => "",
        "Message" => ""
    ];
    public function resolve($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {
        $code = rand(100000, 999999);  // Generate a 6-digit verification code
        $expired_at = Carbon::now()->addMinutes(5)->format("Y-m-d H:i:s");
        $cooldownPeriod = Carbon::now()->subMinutes(5);  // 5-minute cooldown period
        $ipAddress = request()->ip();
    
        // Step 1: Check if the user already exists with the given mobile number and country code
        $user = User::where('country_code', $args['country_code'])
                    ->where('mobile', $args['country_code'].$args['mobile'])
                    ->first();
    
        // Handle existing user logic
        if ($user) {
            // Check if mobile is already verified
            if ($user->mobile_is_verified) {
                return Error::createLocatedError("This mobile number is already verified.");
            }
    
            // Ensure the user hasn't requested a code within the past 5 minutes
            if ($user->last_attempt_at && Carbon::parse($user->last_attempt_at)->gt($cooldownPeriod)) {
                return Error::createLocatedError("You can only request a new code every 5 minutes. Please wait.");
            }
    
            // Update user with a new code and update the last attempt time
            $user->sent_code = $code;
            $user->code_expired_at = $expired_at;
            $user->last_attempt_at = Carbon::now();
            $user->save();
    
        } else {
            // Create a new user record if it doesn't exist
            $user = User::create([
                'country_code' => $args['country_code'],
                'mobile' => $args['country_code'].$args['mobile'],
                'sent_code' => $code,
                'code_expired_at' => $expired_at,
                'last_attempt_at' => Carbon::now(),
                'status' => Status::None,
            ]);
        }
    
        return [
            'user_id' => $user->id,
            'status' => 'SUCCESS',
        ];
    }
    
    
    public function VerifyMobileresolve($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {
        $user = User::find($args['user_id']);
        
        if (!$user) {
            return Error::createLocatedError("User not found!");
        }
    
        // Check if the mobile is already verified
        if ($user->mobile_is_verified) {
            return Error::createLocatedError("This mobile number is already verified.");
        }
    
        // Verify the code and check if it’s expired
        if ($user->sent_code != $args['code'] || Carbon::now()->gt($user->code_expired_at)) {
            return Error::createLocatedError("Invalid or expired code.");
        }
    
        // Mark mobile as verified
        $user->mobile_is_verified = true;
        $user->status = Status::New;
        $user->save();
    
        return [
            "Code" => 200,
            "Message" => "USER MOBILE IS VERIFIED"
        ];
    }
    
    
}
