<?php

namespace App\GraphQL\Queries\PersonDetails;

use App\Models\PersonDetail;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesUser;


final class GetPersonDetail
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
    function resolvePersonDetail($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // $this->userId = $this->getUserId();

        // $personDetail = PersonDetail::find($args['id']);       

        //  // If the person has a profile picture, build the full URL
        // if ($personDetail && $personDetail->profile_picture) {
        //     $personDetail->profile_picture = url('storage/profile_pictures/' . $personDetail->profile_picture);
        // }
        // return $personDetail;

        $PersonDetail = $this->getModelByAuthorization(PersonDetail::class, $args);
        return $PersonDetail->first();
    }
}

