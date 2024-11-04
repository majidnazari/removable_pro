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

use GraphQL\Error\Error;

use Log;

class RegisterMobile
{
    public $today ;

    const MAX_USER_ATTEMPTS = 3;
    const MAX_IP_ATTEMPTS = 6;
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
        $expired_at = Carbon::now()->addMinutes(2)->format("Y-m-d H:i:s");
    
        // Check if the user already exists with the given mobile number and country code
        $user = User::where('country_code', $args['country_code'])
                    ->where('mobile', $args['mobile'])
                    ->first();

        Log::info("the user is:" . json_encode($user));            
    
        if ($user) {
            // Check if mobile is already verified
            if ($user->mobile_is_verified) {
                return Error::createLocatedError("This mobile number is already verified.");
            }
    
            // Check if the user has exceeded the attempt limit
            if ($user->user_attempt_time >= 3) {
                return Error::createLocatedError("You have exceeded the maximum number of verification attempts. Please try again later.");
            }
    
            // Update user with a new code and increment attempt count
            $user->sent_code = $code;
            $user->code_expired_at = $expired_at;
            $user->user_attempt_time += 1;
            $user->save();
    
        } else {
            // Create a new user record if it doesn't exist
            $user = User::create([
                'country_code' => $args['country_code'],
                'mobile' => $args['mobile'],
                'sent_code' => $code,
                'code_expired_at' => $expired_at,
                'user_attempt_time' => 1,  // Start with the first attempt
                'status' => 'None',
            ]);
        }
    
        // Update or create IP tracking record
        IpTracking::firstOrCreate(['ip' => request()->ip()]);
    
        return [
            'user_id' => $user->id,
            'status' => 'SUCCESS',
        ];
    }
    
    
    public function VerifyMobileresolve($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {
        $ipAddress = request()->ip();
        $user = User::find($args['user_id']);
        
        if (!$user) {
            return Error::createLocatedError("User not found!");
        }
        if ($user->mobile_is_verified) {
            return Error::createLocatedError("Mobile number is already verified and active.");
        }

        // Step 1: Fetch or create login attempt record
        $loginAttempt = LoginAttempt::recordAttempt($user->id, $ipAddress);

        // Step 2: Check if user or IP is blocked
        if ($loginAttempt->expire_blocked_time && Carbon::now()->lt($loginAttempt->expire_blocked_time)) {
            return Error::createLocatedError("Your account is temporarily blocked. Please try again later.");
        }

        if ($loginAttempt->today_attempts > self::MAX_USER_ATTEMPTS) {
            $loginAttempt->expire_blocked_time = Carbon::now()->addMinutes(self::BLOCK_DURATION);
            $loginAttempt->number_of_blocked_times += 1;
            $loginAttempt->save();

            return Error::createLocatedError("Too many attempts. Your account is blocked for 1 day.");
        }

        // Step 3: Verify code
        if ($user->sent_code != $args['code'] || Carbon::now()->gt($user->code_expired_at)) {
            return Error::createLocatedError("Invalid code or expired code!");
        }

        // Step 4: Verification succeeded - reset attempts
        $user->mobile_is_verified = 1;
        $user->status = "Active";
        $user->save();

        $loginAttempt->today_attempts = 0;
        $loginAttempt->total_attempts = 0;
        $loginAttempt->expire_blocked_time = null;
        $loginAttempt->save();

        return [
            "Code" => 200,
            "Message" => "USER MOBILE IS VERIFIED"
        ];
    }
}
