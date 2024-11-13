<?php

namespace App\GraphQL\Mutations\PersonMarriage;

use App\GraphQL\Enums\MarriageStatus;
use App\Models\PersonMarriage;
use GraphQL\Type\Definition\ResolveInfo;
use App\Models\GroupUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Joselfonseca\LighthouseGraphQLPassport\PersonMarriages\PasswordUpdated;
use Joselfonseca\LighthouseGraphQLPassport\Exceptions\ValidationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\GraphQL\Enums\Status;
use Log;

final class CreatePersonMarriage
{
   
    /**
     * @param  null  $_
     * 
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolvePersonMarriage($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {



        //Log::info("the args are:" . json_encode($args));
        $user_id=auth()->guard('api')->user()->id;
        $PersonMarriageModel = [
            "creator_id" => $user_id,

            "man_id" => $args['man_id'],
            "woman_id" => $args['woman_id'],
            "editor_id" => $args['editor_id'] ?? null,
            "marriage_status" => $args['marriage_status'] ?? MarriageStatus::Related , // Default to 'None' if not provided
            //"marriage_status" => $args['spouse_status'] ?? 'None', // Default to 'None' if not provided
            "status" => $args['status'] ?? Status::Active, // Default to 'Active' if not provided
            "marriage_date" => $args['marriage_date'] ?? null,
            "divorce_date" => $args['divorce_date'] ?? null
        ];
        
        // Check if a similar record exists based on unique constraints or business logic
        $is_exist = PersonMarriage::where('man_id', $args['man_id'])
        ->where('woman_id', $args['woman_id'])
        ->where('marriage_status', $args['marriage_status'] ?? MarriageStatus::None)
       // ->where('spouse_status', $args['spouse_status'] ?? 'None')
        ->first();
        
        if ($is_exist) {
            return Error::createLocatedError("PersonMarriage-CREATE-RECORD_IS_EXIST");
        }
        $PersonMarriageResult = PersonMarriage::create($PersonMarriageModel);
        return $PersonMarriageResult;
    }
}