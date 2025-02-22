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
use App\GraphQL\Enums\UserStatus;
use App\Traits\AuthUserTrait;



use GraphQL\Error\Error;

use Log;

class ForgotPassword extends BaseAuthResolver
{
    public $today;

    const MAX_USER_ATTEMPTS = 3;
    const MAX_IP_ATTEMPTS = 2;
    const BLOCK_DURATION = 1440; // in minutes (1 day)

    public function __construct()
    {
        $this->today = Carbon::now()->format("Y-m-d");
    }

    public $error = [
        "Code" => "",
        "Message" => ""
    ];
    public function resolve($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $code = rand(100000, 999999);  // Generate a 6-digit verification code
        $expired_at = Carbon::now()->addMinutes(5)->format("Y-m-d H:i:s");
        $cooldownPeriod = Carbon::now()->subMinutes(5);  // 5-minute cooldown period
        $ipAddress = request()->ip();

        // Step 1: Check if the user already exists with the given mobile number and country code
        $user = User::where('country_code', $args['country_code'])
            ->where('mobile', $args['country_code'] . $args['mobile'])
            ->first();

        // Handle existing user logic
        if ($user) {
            // Check if mobile is already verified
            if (!$user->mobile_is_verified) {
                return Error::createLocatedError("This mobile number is not verified yet please register first!");
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
            return Error::createLocatedError("User not found!");
        }

        return [
            'user_id' => $user->id,
            'status' => 'SUCCESS',
        ];
    }


    // public function verifyForgotPassword($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    // {

    //     $user = User::where('mobile', $args['country_code'] . $args['mobile'])
    //         ->where('status', UserStatus::Active)
    //         ->where('sent_code', operator: $args['code'])
    //         ->where('mobile_is_verified', true)->first();

    //     if (!$user) {
    //         return Error::createLocatedError('User not found');
    //     }

    //     // Check if the code is expired
    //     if ($user->code_expired_at && Carbon::parse(Carbon::now())->gt($user->code_expired_at)) {
    //         // Log or return additional details about the expiration
    //         return Error::createLocatedError("The code is expired. It expired at: " . $user->code_expired_at->toDateTimeString());
    //     }

    //     //Log::info("the code_expired_at is: ". Carbon::parse($user->code_expired_at) . " and now is :" . Carbon::now() ." and result :". Carbon::parse(Carbon::now())->gt($user->code_expired_at));


    //     // Check if password change attempts exceed the daily limit
    //     $today = Carbon::today();
    //     if ($user->last_password_change_attempt && Carbon::parse($user->last_password_change_attempt)->isSameDay($today)) {
    //         if ($user->password_change_attempts >= 2) {
    //             return Error::createLocatedError("You cannot change your password more than 2 times in a day.");
    //         }
    //     } else {
    //         // Reset attempts for a new day
    //         $user->password_change_attempts = 0;
    //     }

    //     // Increment attempt count and update last attempt timestamp
    //     $user->password_change_attempts += 1;
    //     $user->last_password_change_attempt = Carbon::now();
    //     if ($user->code_expired_at && Carbon::parse(Carbon::now())->gt($user->code_expired_at)) {
    //         return Error::createLocatedError("the code is expired please send code again.");
    //     }

    //     //$code=rand(0,99999999);

    //     // $user->sent_code=$code;
    //     //$user->code_expired_at= $expired_at;
    //     // $user->last_attempt_at = Carbon::now();
    //     // $user->save();

    //     $user->password = Hash::make($args['password']);
    //     //$user->sent_code=0;
    //     $user->code_expired_at = Carbon::now();
    //     $user->save();

    //     $credentials = $this->buildCredentials([
    //         //'username' => $args[config('lighthouse-graphql-passport.username')],
    //         'username' => $user->mobile,
    //         'password' => $args['password'],
    //     ]);

    //     //Log::info("cred is:".  json_encode($credentials));

    //     $response = $this->makeRequest($credentials);

    //     return [
    //         'tokens' => $response,
    //         'mobile' => $args['mobile'],
    //         'status' => 'SUCCESS',
    //     ];
    // }



    public function verifyForgotPassword($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // Fetch the user with required conditions
        $user = User::where([
            'mobile' => $args['country_code'] . $args['mobile'],
            'status' => UserStatus::Active,
            'sent_code' => $args['code'],
            'mobile_is_verified' => true,
        ])->first();

        // Return an error if the user is not found
        if (!$user) {
            return Error::createLocatedError(config('auth.password_reset.errors.user_not_found', 'User not found'));
        }

        // Check if the code is expired
        if ($user->code_expired_at && Carbon::now()->gt($user->code_expired_at)) {
            return Error::createLocatedError(
                config('auth.password_reset.errors.code_expired', 'The code is expired') . ". Expired at: " . $user->code_expired_at->toDateTimeString()
            );
        }

        // Check and reset password change attempts if the day has changed
        $today = Carbon::today();
        if (!$user->last_password_change_attempt || !Carbon::parse($user->last_password_change_attempt)->isSameDay($today)) {
            $user->password_change_attempts = 0; // Reset attempts for a new day
        }

        // Check if daily password change attempts have been exceeded
        $maxAttempts = config('auth.password_reset.max_attempts', 2);
        if ($user->password_change_attempts >= $maxAttempts) {
            return Error::createLocatedError(config('auth.password_reset.errors.max_attempts_exceeded', 'You cannot change your password more than ' . $maxAttempts . ' times in a day.'));
        }

        // Update user details
        $user->password_change_attempts++;
        $user->last_password_change_attempt = Carbon::now();
        $user->password = Hash::make($args['password']);
        $user->code_expired_at = null; // Invalidate the code
        $user->save();

        // Build credentials for authentication
        $credentials = $this->buildCredentials([
            'username' => $user->mobile,
            'password' => $args['password'],
        ]);

        // Perform authentication
        //$response = $this->makeRequest($credentials);


        try {
            $response = $this->makeRequest($credentials);
        } catch (\Exception $e) {
            return Error::createLocatedError('Authentication failed. Please try again.');
        }


        // Return success response
        return [
            'tokens' => $response,
            'mobile' => $args['mobile'],
            'status' => config('auth.password_reset.success_status', 'SUCCESS'),
        ];
    }
}
