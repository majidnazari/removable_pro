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
use App\Models\UserAnswer;
use DB;
use Log;

class Register extends BaseAuthResolver
{
    /**
     * @param $rootValue
     * @param  array  $args
     * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext|null  $context
     * @param  \GraphQL\Type\Definition\ResolveInfo  $resolveInfo
     * @return array
     *
     * @throws \Exception
     */
    public function resolve($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {
        // Log::info("the inside of resolve is running");
        $code=rand(0,99999999);
        $code_expired_at=Carbon::now()->addMinutes(2)->format("Y-m-d H:i:s");
        $args['sent_code']=$code;
        $args['code_expired_at']=$code_expired_at;
        $model = $this->createAuthModel($args);

        //Log::info("the user is:" . json_encode($model));

        $this->validateAuthModel($model);

        // if ($model instanceof MustVerifyEmail) {
        //     $model->sendEmailVerificationNotification();

        //     event(new Registered($model));

        //     return [
        //         'tokens' => [],
        //         'status' => 'MUST_VERIFY_EMAIL',
        //     ];
        // }
        $credentials = $this->buildCredentials([
            'username' => $args[config('lighthouse-graphql-passport.username')],
            'password' => $args['password'],
        ]);
        $user = $model->where(config('lighthouse-graphql-passport.username'), $args[config('lighthouse-graphql-passport.username')])->first();
        // Log::info("the model of fetched is" . json_encode($user));
        // Log::info("the makeRequest and credentials is" . json_encode($credentials));

       // $response = $this->makeRequest($credentials);
        //Log::info("the response  is:" . json_encode($response));

        $response['user'] = $user;
        event(new Registered($user));

        return [
            //'tokens' => $response,
            'user_id' => $user->id,
            'status' => 'SUCCESS',
        ];
    }

    protected function validateAuthModel($model): void
    {
        $authModelClass = $this->getAuthModelFactory()->getClass();

        if ($model instanceof $authModelClass) {
            return;
        }

        throw new \RuntimeException("Auth model must be an instance of {$authModelClass}");
    }

    protected function createAuthModel(array $data): Model
    {
        $input = collect($data)->except('password_confirmation')->toArray();
        $input['password'] = Hash::make($input['password']);

        return $this->getAuthModelFactory()->create($input);
    }



    public function CompleteUserRegistrationresolve($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {

        $user=User::where('id',$args['user_id'])
        ->where('status','New')
        ->whereNull('password')
        ->first();
        if(!$user)
        {
            throw new \RuntimeException('User not found');
        }

        // Extract the country code and mobile number separately
        //$countryCodeLength = strlen($user->country_code);
        //$pureMobileNumber = substr($user->mobile, $countryCodeLength); // remove country code prefix

        $user->password=Hash::make($args['password']);
        $user->status="Active";
        $user->save();
        
        $credentials = $this->buildCredentials([
            //'username' => $args[config('lighthouse-graphql-passport.username')],
            'username' => $user->mobile ,
            'password' => $args['password'],
        ]);

        //Log::info("cred is:".  json_encode($credentials));

        $response = $this->makeRequest($credentials);
       
        return [
            'tokens' =>$response,
            'user_id' => $user->id,
            'status' => 'SUCCESS',
        ];
       
    }

}
