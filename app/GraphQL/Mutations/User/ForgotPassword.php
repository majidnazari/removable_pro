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
    public function resolve($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
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


    public function verifyForgotPassword($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {

        $user = User::where('mobile', $args['country_code'] . $args['mobile'])
            ->where('status', UserStatus::Active)
            ->where('sent_code', operator: $args['code'])
            ->where('mobile_is_verified', true)->first();

        if (!$user) {
            return Error::createLocatedError('User not found');
        }

        //Log::info("the code_expired_at is: ". Carbon::parse($user->code_expired_at) . " and now is :" . Carbon::now() ." and result :". Carbon::parse(Carbon::now())->gt($user->code_expired_at));


        // Check if password change attempts exceed the daily limit
        $today = Carbon::today();
        if ($user->last_password_change_attempt && Carbon::parse($user->last_password_change_attempt)->isSameDay($today)) {
            if ($user->password_change_attempts >= 2) {
                return Error::createLocatedError("You cannot change your password more than 2 times in a day.");
            }
        } else {
            // Reset attempts for a new day
            $user->password_change_attempts = 0;
        }

        // Increment attempt count and update last attempt timestamp
        $user->password_change_attempts += 1;
        $user->last_password_change_attempt = Carbon::now();
        if ($user->code_expired_at && Carbon::parse(Carbon::now())->gt($user->code_expired_at)) {
            return Error::createLocatedError("the code is expired please send code again.");
        }

        //$code=rand(0,99999999);

        // $user->sent_code=$code;
        //$user->code_expired_at= $expired_at;
        // $user->last_attempt_at = Carbon::now();
        // $user->save();

        $user->password = Hash::make($args['password']);
        //$user->sent_code=0;
        $user->code_expired_at = Carbon::now();
        $user->save();

        $credentials = $this->buildCredentials([
            //'username' => $args[config('lighthouse-graphql-passport.username')],
            'username' => $user->mobile,
            'password' => $args['password'],
        ]);

        //Log::info("cred is:".  json_encode($credentials));

        $response = $this->makeRequest($credentials);

        return [
            'tokens' => $response,
            'mobile' => $args['mobile'],
            'status' => 'SUCCESS',
        ];
    }


}
