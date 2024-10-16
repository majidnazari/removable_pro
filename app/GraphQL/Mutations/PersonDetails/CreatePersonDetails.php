<?php

namespace App\GraphQL\Mutations\PersonDetails;

use App\Models\PersonDetail;
use GraphQL\Type\Definition\ResolveInfo;
use App\Models\GroupUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Joselfonseca\LighthouseGraphQLPassport\PersonDetails\PasswordUpdated;
use Joselfonseca\LighthouseGraphQLPassport\Exceptions\ValidationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use Log;

final class CreatePersonDetails
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
    public function resolvePersonDetail($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {



        //Log::info("the args are:" . json_encode($args));
        //$user_id=auth()->guard('api')->user()->id;
        $PersonDetailsModel = [
            "create_id" => 1,
            "person_id" => $args['person_id'],
            "profile_picture" => $args['profile_picture'] ?? null,
            "gendar" => $args['gendar'] ?? 'None', // Default to 'None' if not provided
            "physical_condition" => $args['physical_condition'] ?? 'Healthy' // Default to 'Healthy' if not provided
        ];
        
        // Check if a similar details profile already exists for the same person_id
        $is_exist = PersonDetail::where('person_id', $args['person_id'])->first();
        
        if ($is_exist) {
            return Error::createLocatedError("PersonDetail-CREATE-RECORD_IS_EXIST");
        }
        $PersonDetailResult = PersonDetail::create($PersonDetailsModel);
        return $PersonDetailResult;
    }
}