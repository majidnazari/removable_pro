<?php

namespace App\GraphQL\Queries\MajorField;

use App\Models\MajorField;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesUser;


final class GetMajorField
{
    use AuthUserTrait;
    use AuthorizesUser;

    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    function resolveMajorField($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // $this->userId = $this->getUserId();

        // $MajorField = MajorField::where('id', $args['id']);       
        // return $MajorField->first();

        $MajorField = $this->getModelByAuthorization(MajorField::class, $args);
        return $MajorField->first();
    }
}