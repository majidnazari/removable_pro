<?php

namespace App\GraphQL\Mutations\FamilyEvent;

use App\Models\FamilyEvent;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\GraphQL\Enums\Status;
use App\Traits\AuthUserTrait;
use App\Traits\DuplicateCheckTrait;

use Log;

final class CreateFamilyEvent
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
    public function resolveFamilyEvent($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    { 
        $this->userId = $this->getUserId();

        $FamilyEventResult=[
            "creator_id" =>  $this->userId,
            "person_id" => $args['person_id'],
            "event_id" => $args['event_id'],
            "category_content_id" => $args['category_content_id'] ,
            "group_category_id" => $args['group_category_id'] ?? null,
            // "title" => $args['title'],
            "event_date" => $args['event_date'],
            "status" => $args['status'] ?? Status::Active           
        ];
        // $is_exist= FamilyEvent::where('person_id',$args['person_id'])
        // ->where('status',$args['status'])
        // ->where('event_date',$args['event_date'])
        // ->first();
        // if($is_exist)
        //  {
        //          return Error::createLocatedError("FamilyEvent-CREATE-RECORD_IS_EXIST");
        //  }
        $this->checkDuplicate(new FamilyEvent(), $FamilyEventResult);
        $FamilyEventResult_result=FamilyEvent::create($FamilyEventResult);
        return $FamilyEventResult_result;
    }
}