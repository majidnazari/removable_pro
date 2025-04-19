<?php

namespace App\GraphQL\Mutations\NaslanRelationship;

use App\Models\NaslanRelationship;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\GraphQL\Enums\Status;
use App\Traits\AuthUserTrait;

use Log;

final class CreateNaslanRelationship
{
    use AuthUserTrait;
    protected $userId;
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolveNaslanRelationship($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $this->userId = $this->getUserId();

        $NaslanRelationResult = [
            "status" => $args['status'] ?? Status::Active,
            "title" => $args['title'],
        ];
        $is_exist = NaslanRelationship::where('title', $args['title'])->first();
        if ($is_exist) {
            return Error::createLocatedError("NaslanRelation-CREATE-RECORD_IS_EXIST");
        }
        $NaslanRelationResult_result = NaslanRelationship::create($NaslanRelationResult);
        return $NaslanRelationResult_result;
    }
}