<?php

namespace App\GraphQL\Mutations\User;


use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Joselfonseca\LighthouseGraphQLPassport\Events\UserLoggedIn;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Joselfonseca\LighthouseGraphQLPassport\GraphQL\Mutations\BaseAuthResolver;
use Illuminate\Support\Facades\Hash;
use App\Traits\MergeRequestTrait;

use Log;

class Login extends BaseAuthResolver
{
    use MergeRequestTrait;

    /**
     * @param $rootValue
     * @param  array  $args
     * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext  $context
     * @param  \GraphQL\Type\Definition\ResolveInfo  $resolveInfo
     * @return array
     *
     * @throws \Exception
     */
    public function resolve($rootValue, array $args, GraphQLContext $context , ResolveInfo $resolveInfo)
    {

        //Log::info("the new pass is:" . Hash::make("12345678"));
        $credentials = $this->buildCredentials($args);
        //Log::info("the credentials is :" .json_encode( $credentials));

        $response = $this->makeRequest($credentials);


        $user = $this->findUser($args['username']);

       // Log::info("the user is:" . json_encode($user));

        $this->validateUser($user);

        event(new UserLoggedIn($user));

        // Log::info("the all MR are:" . json_encode($this->getRelatedUserIds()));

        return array_merge(
            $response,
            [
                'user' => $user,
            ]
        );
    }

    protected function validateUser($user)
    {
        $authModelClass = $this->getAuthModelClass();
        if ($user instanceof $authModelClass && $user->exists) {
            return;
        }

        throw (new ModelNotFoundException())
            ->setModel($authModelClass);
    }

    protected function findUser(string $username)
    {
        $model = $this->makeAuthModelInstance();

        if (method_exists($model, 'findForPassport')) {
            return $model->findForPassport($username);
        }

        return $model::query()
            ->where(config('lighthouse-graphql-passport.username'), $username)
            ->first();
    }
}
