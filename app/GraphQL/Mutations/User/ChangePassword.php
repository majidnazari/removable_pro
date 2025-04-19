<?php

namespace App\GraphQL\Mutations\User;

use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Joselfonseca\LighthouseGraphQLPassport\GraphQL\Mutations\BaseAuthResolver;
use Carbon\Carbon;
use App\Models\User;
use App\Traits\AuthUserTrait;
use Exception;
use App\Exceptions\CustomValidationException;

use GraphQL\Error\Error;
use App\GraphQL\Enums\UserStatus;

use DB;
use Log;

class ChangePassword extends BaseAuthResolver
{

    use AuthUserTrait;
    /**
     * @param $rootValue
     * @param  array  $args
     * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext|null  $context
     * @param  \GraphQL\Type\Definition\ResolveInfo  $resolveInfo
     * @return array
     *
     * @throws \Exception
     */
    public function resolve($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): array|Error
    {
        $this->userId = $this->getUserId();

        $expired_at = Carbon::now()->addMinutes(5)->format("Y-m-d H:i:s");
        //$cooldownPeriod = Carbon::now()->subMinutes(5);  // 5-minute cooldown period

        $user = User::where('mobile', $args['country_code'] . $args['mobile'])
            ->where('status', UserStatus::Active)
            ->where('mobile_is_verified', true)
            ->first();

        if (!$user) {
            throw new CustomValidationException("User not found", "کاربر پیدا نشد", 404);

        }
        if ($this->userId != $user->id) {
            throw new CustomValidationException("access denied!", "دسترسی ممنوع است!", 403);

        }


        if ($user->code_expired_at && Carbon::parse($user->code_expired_at)->gt(Carbon::now())) {
            throw new CustomValidationException("You can only request a new code every 5 minutes. Please wait.", "شما فقط می توانید هر 5 دقیقه یک کد جدید درخواست کنید. لطفا صبر کنید.", 429);

        }

        $code = 159951;//rand(100000, 999999);

        $user->sent_code = $code;
        $user->code_expired_at = $expired_at;
        // $user->last_attempt_at = Carbon::now();
        $user->save();

        return [
            //'tokens' => $response,
            'mobile' => $args['mobile'],
            'status' => 'SUCCESS',
        ];
    }

    public function changePasswordResolve($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $this->userId = $this->getUserId();

        $user = User::where('mobile', $args['country_code'] . $args['mobile'])
            ->where('status', UserStatus::Active)
            ->where('sent_code', operator: $args['code'])
            ->where('mobile_is_verified', true)->first();


        if (!$user) {
            throw new CustomValidationException("User not found", "کاربر پیدا نشد", 404);

        }
        if ($this->userId != $user->id) {
            throw new CustomValidationException("access denied!", "دسترسی ممنوع است!", 403);

        }


        $today = Carbon::today(); // Current date without time (midnight of today)

        // Check if the last password change attempt is on a different day
        if ($user->last_password_change_attempt && !Carbon::parse($user->last_password_change_attempt)->isSameDay($today)) {
            // Reset attempts for the new day
            $user->password_change_attempts = 0;
        }

        // Now check if password change attempts exceed the daily limit
        if ($user->password_change_attempts >= 2) {
            throw new CustomValidationException("You cannot change your password more than 2 times in a day.", "شما نمی توانید رمز عبور خود را بیش از 2 بار در روز تغییر دهید.", 429);

        }

        // If the verification code is expired, return an error
        if ($user->code_expired_at && Carbon::parse(Carbon::now())->gt($user->code_expired_at)) {
            throw new CustomValidationException("The code is expired. Please send the code again.", "کد منقضی شده است. لطفا دوباره کد را ارسال کنید.", 410);

        }

        // Increment attempt count and update last attempt timestamp
        $user->password_change_attempts += 1;
        $user->last_password_change_attempt = Carbon::now();
        $user->password = Hash::make($args['password']);
        $user->code_expired_at = Carbon::now();
        $user->save();

        $credentials = $this->buildCredentials([
            //'username' => $args[config('lighthouse-graphql-passport.username')],
            'username' => $user->mobile,
            'password' => $args['password'],
        ]);


        $response = $this->makeRequest($credentials);

        return [
            'tokens' => $response,
            'mobile' => $args['mobile'],
            'status' => 'SUCCESS',
        ];
    }

}
