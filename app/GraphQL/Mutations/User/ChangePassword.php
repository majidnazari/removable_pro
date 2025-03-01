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
            return Error::createLocatedError("User not found");
        }
        if ($this->userId != $user->id) {
            return Error::createLocatedError("access denied!");
        }


        if ($user->code_expired_at && Carbon::parse($user->code_expired_at)->gt(Carbon::now())) {
            return Error::createLocatedError("You can only request a new code every 5 minutes. Please wait.");
        }

        // Log::info("the inside of resolve is running");
        $code = rand(100000, 999999);

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
        //$expired_at = Carbon::now()->addMinutes(5)->format("Y-m-d H:i:s");
        //$cooldownPeriod = Carbon::now()->subMinutes(5);  // 5-minute cooldown period

        $user = User::where('mobile', $args['country_code'] . $args['mobile'])
            ->where('status', UserStatus::Active)
            ->where('sent_code', operator: $args['code'])
            ->where('mobile_is_verified', true)->first();


        if (!$user) {
            return Error::createLocatedError('User not found');
        }
        if ($this->userId != $user->id) {
            return Error::createLocatedError("access denied!");
        }

        //Log::info("the code_expired_at is: ". Carbon::parse($user->code_expired_at) . " and now is :" . Carbon::now() ." and result :". Carbon::parse(Carbon::now())->gt($user->code_expired_at));

        $today = Carbon::today(); // Current date without time (midnight of today)

        // Check if the last password change attempt is on a different day
        if ($user->last_password_change_attempt && !Carbon::parse($user->last_password_change_attempt)->isSameDay($today)) {
            // Reset attempts for the new day
            $user->password_change_attempts = 0;
        }
        
        // Now check if password change attempts exceed the daily limit
        if ($user->password_change_attempts >= 2) {
            return Error::createLocatedError("You cannot change your password more than 2 times in a day.");
        }
        
        // If the verification code is expired, return an error
        if ($user->code_expired_at && Carbon::parse(Carbon::now())->gt($user->code_expired_at)) {
            return Error::createLocatedError("The code is expired. Please send the code again.");
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

        //Log::info("cred is:".  json_encode($credentials));

        $response = $this->makeRequest($credentials);

        return [
            'tokens' => $response,
            'mobile' => $args['mobile'],
            'status' => 'SUCCESS',
        ];
    }

}
