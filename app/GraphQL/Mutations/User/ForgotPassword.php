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
use App\Exceptions\CustomValidationException;
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
        $code = 159951;//rand(100000, 999999);  // Generate a 6-digit verification code
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
                throw new CustomValidationException("This mobile number is not verified yet please register first!", "این شماره موبایل هنوز تایید نشده است لطفا ابتدا ثبت نام کنید!", 403);

            }

            // Ensure the user hasn't requested a code within the past 5 minutes
            if ($user->last_attempt_at && Carbon::parse($user->last_attempt_at)->gt($cooldownPeriod)) {
                throw new CustomValidationException("This mobile number is not verified yet please register first!", "فقط می توانید هر 5 دقیقه یک کد جدید درخواست کنید. لطفا صبر کنید.", 429);

            }

            // Update user with a new code and update the last attempt time
            $user->sent_code = $code;
            $user->code_expired_at = $expired_at;
            $user->last_attempt_at = Carbon::now();

            $user->save();

        } else {
            throw new CustomValidationException("User not found!", "کاربر پیدا نشد!", 404);

        }

        return [
            'user_id' => $user->id,
            'status' => 'SUCCESS',
        ];
    }

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
            throw new CustomValidationException("User not found", "کاربر پیدا نشد", 404);

        }

        // Check if the code is expired
        if ($user->code_expired_at && Carbon::now()->gt($user->code_expired_at)) {
            throw new CustomValidationException("The code is expired. Please send the code again.", "کد منقضی شده است. لطفا دوباره کد را ارسال کنید.", 410);

        }

        // Check and reset password change attempts if the day has changed
        $today = Carbon::today();
        if (!$user->last_password_change_attempt || !Carbon::parse($user->last_password_change_attempt)->isSameDay($today)) {
            $user->password_change_attempts = 0; // Reset attempts for a new day
        }

        // Check if daily password change attempts have been exceeded
        $maxAttempts = config('auth.password_reset.max_attempts', 2);
        if ($user->password_change_attempts >= $maxAttempts) {
            throw new CustomValidationException("You cannot change your password more than ' . $maxAttempts . ' times in a day.", "شما نمی توانید رمز عبور خود را بیش از " . $maxAttempts . "بار در در روز تغییر دهید.", 429);

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


        try {
            $response = $this->makeRequest($credentials);
        } catch (CustomValidationException $e) {

            throw new CustomValidationException($e->getMessage(), $e->getMessage(), 500);

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
