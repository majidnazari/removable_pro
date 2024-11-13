<?php

namespace App\GraphQL\Mutations\PersonScore;

use App\Models\PersonScore;
use GraphQL\Type\Definition\ResolveInfo;
use App\Models\GroupUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Joselfonseca\LighthouseGraphQLPassport\PersonScore\PasswordUpdated;
use Joselfonseca\LighthouseGraphQLPassport\Exceptions\ValidationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\GraphQL\Enums\Status;

use Log;

final class CreatePersonScore
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
    public function resolvePersonScore($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {

        //Log::info("the args are:" . json_encode($args));
        $user_id=auth()->guard('api')->user()->id;
        $PersonScoreModel = [
            "creator_id" =>  $user_id,
            "person_id" => $args['person_id'],
            "score_id" => $args['score_id'],
            
            "score_level" => $args['score_level'] ,
            "status" => $args['status'] ?? Status::Active, // Default to 'None' if not provided
        ];
        
        // Check if a similar details profile already exists for the same person_id
        $is_exist = PersonScore::where('person_id', $args['person_id'])->first();
        
        if ($is_exist) {
            return Error::createLocatedError("PersonScore-CREATE-RECORD_IS_EXIST");
        }
        $PersonScoreResult = PersonScore::create($PersonScoreModel);
        return $PersonScoreResult;
    }
}