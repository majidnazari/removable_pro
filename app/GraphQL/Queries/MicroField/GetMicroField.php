<?php

namespace App\GraphQL\Queries\MicroField;

use App\Models\MicroField;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesUser;


final class GetMicroField
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
    function resolveMicroField($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // $this->userId = $this->getUserId();

        // $MicroField = MicroField::where('id', $args['id']);       
        // return $MicroField->first();

        $MicroField = $this->getModelByAuthorization(MicroField::class, $args);
        return $MicroField->first();
    }
}