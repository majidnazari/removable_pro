<?php
namespace App\GraphQL\Mutations\User;


use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Auth;
use Joselfonseca\LighthouseGraphQLPassport\Events\UserLoggedOut;
use Joselfonseca\LighthouseGraphQLPassport\Exceptions\AuthenticationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Joselfonseca\LighthouseGraphQLPassport\GraphQL\Mutations\BaseAuthResolver;
use App\Traits\AuthUserTrait;

use App\Exceptions\CustomValidationException;

class Logout extends BaseAuthResolver
{
    use AuthUserTrait;
    protected $userId;

    /**
     * @param $rootValue
     * @param  array  $args
     * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext|null  $context
     * @param  \GraphQL\Type\Definition\ResolveInfo  $resolveInfo
     * @return array
     *
     * @throws \Exception
     */
    public function resolve($rootValue, array $args, ?GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $user = $this->getUser();
        // revoke user's token
        Auth::guard('api')->user()->token()->revoke();

        event(new UserLoggedOut($user));

        return [
            'status' => 'TOKEN_REVOKED',
            'message' => __('Your session has been terminated'),
        ];
    }
}
