<?php

namespace App\GraphQL\Mutations\TalentHeader;

use App\Models\TalentHeader;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\GraphQL\Enums\Status;
use App\GraphQL\Enums\Star;
use App\Traits\AuthUserTrait;
use App\Traits\DuplicateCheckTrait;
use App\Traits\FindOwnerTrait;
use Log;

final class CreateTalentHeader
{
    use AuthUserTrait;
    use DuplicateCheckTrait;
    use FindOwnerTrait;

    protected $userId;

    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolveTalentHeader($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {

        $this->userId = $this->getUserId();

        $talentHeaderModel = [
            "creator_id" => $this->userId,
            "group_category_id" => $args['group_category_id'],
            "person_id" => $args['person_id'] ?? $this->findOwner()->id,
            "title" => $args['title'],
            "end_date" => $args['end_date'],
            "status" => $args['status'] ?? status::Active
        ];

        $this->checkDuplicate(new TalentHeader(), $talentHeaderModel);
        $talentHeaderResult = TalentHeader::create($talentHeaderModel);
        return $talentHeaderResult;
    }
}