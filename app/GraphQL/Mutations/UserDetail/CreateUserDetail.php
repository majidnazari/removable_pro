<?php

namespace App\GraphQL\Mutations\UserDetail;

use App\Models\UserDetail;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\GraphQL\Enums\Status;
use Illuminate\Support\Facades\Auth;
use App\Traits\AuthUserTrait;
use App\Traits\DuplicateCheckTrait;
use App\Exceptions\CustomValidationException;

use Exception;
use Log;

final class CreateUserDetail
{
    use AuthUserTrait;
    use DuplicateCheckTrait;

    protected $userId;

    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolveUserDetail($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $this->user = $this->getUser();

        $UserDetailResult = [
            "creator_id" => $this->user->id,
            "status" => $args['status'] ?? Status::Active,
            "mobile" => $args['mobile'],
            "last_seen_family_board_id" => $args['last_seen_family_board_id'],
        ];

        if ($this->user->mobile !== $args['mobile']) {
            throw new CustomValidationException("The provided mobile does not belong to the logged-in user.", "تلفن همراه ارائه شده به کاربر وارد شده تعلق ندارد.", 422);

        }

        $this->checkDuplicate(
            new UserDetail(),
            $UserDetailResult
        );
        $UserDetailResult_result = UserDetail::create($UserDetailResult);
        return $UserDetailResult_result;
    }
}