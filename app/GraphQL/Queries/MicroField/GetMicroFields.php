<?php

namespace App\GraphQL\Queries\MicroField;

use App\Models\MicroField;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesUser;
use App\Traits\SearchQueryBuilder;


final class GetMicroFields
{
    use AuthUserTrait;
    use AuthorizesUser;
    use SearchQueryBuilder;
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    function resolveMicroField($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // $this->userId = $this->getUserId();

        // $MicroFields = MicroField::where('deleted_at', null);
        // return $MicroFields;

        $query = $this->getModelByAuthorization(MicroField::class, $args, true);
        $query = $this->applySearchFilters( $query, $args);
        return  $query;
    }
}