<?php

namespace App\GraphQL\Mutations\FamilyEvent;

use App\Models\FamilyEvent;
use GraphQL\Type\Definition\ResolveInfo;
use App\Models\GroupUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Joselfonseca\LighthouseGraphQLPassport\FamilyEvents\PasswordUpdated;
use Joselfonseca\LighthouseGraphQLPassport\Exceptions\ValidationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use Log;

final class CreateFamilyEvent
{
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
       
        //Log::info("the args are:" . json_encode($args));
        //$user_id=auth()->guard('api')->user()->id;
        $FamilyEventResult=[
            "creator_id" => 1,
            "person_id" => $args['person_id'],
            "event_id" => $args['event_id'],
            // "title" => $args['title'],
            "event_date" => $args['event_date'],
            "status" => $args['status']            
        ];
        $is_exist= FamilyEvent::where('person_id',$args['person_id'])
        ->where('status',$args['status'])
        ->where('event_date',$args['event_date'])
        ->first();
        if($is_exist)
         {
                 return Error::createLocatedError("FamilyEvent-CREATE-RECORD_IS_EXIST");
         }
        $FamilyEventResult_result=FamilyEvent::create($FamilyEventResult);
        return $FamilyEventResult_result;
    }
}