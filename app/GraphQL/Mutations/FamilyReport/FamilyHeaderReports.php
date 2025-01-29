<?php

namespace App\GraphQL\Mutations\FamilyReport;

use App\GraphQL\Enums\MarriageStatus;
use App\Models\FamilyReport;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\GraphQL\Enums\Status;
use App\Traits\AuthUserTrait;
use App\Traits\DuplicateCheckTrait;
use App\Traits\GetAllowedAllUsersInClan;
use App\Models\Person;
use App\Models\PersonMarriage;
use App\Models\PersonChild;
use App\Models\User;
use Carbon\Carbon;

use Log;

final class FamilyHeaderReports
{
    use AuthUserTrait;
    use DuplicateCheckTrait;
    use GetAllowedAllUsersInClan;

    protected $userId;
    protected $users;

    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolveFamilyHeaderReport($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {
        $this->userId = $this->getUserId();

       
    }
   

}