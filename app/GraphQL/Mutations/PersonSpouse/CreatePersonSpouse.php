<?php

namespace App\GraphQL\Mutations\PersonSpouse;

use App\Models\PersonSpouse;
use GraphQL\Type\Definition\ResolveInfo;
use App\Models\GroupUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Joselfonseca\LighthouseGraphQLPassport\PersonSpouses\PasswordUpdated;
use Joselfonseca\LighthouseGraphQLPassport\Exceptions\ValidationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use Log;

final class CreatePersonSpouse
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
    public function resolvePersonSpouse($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {



        //Log::info("the args are:" . json_encode($args));
        //$user_id=auth()->guard('api')->user()->id;
        $PersonSpouseModel = [
            "creator_id" =>1,

            "person_id" => $args['person_id'],
            "spouse_id" => $args['spouse_id'],
            "editor_id" => $args['editor_id'] ?? null,
            "marrage_status" => $args['marrage_status'] ?? 'None', // Default to 'None' if not provided
            "spouse_status" => $args['spouse_status'] ?? 'None', // Default to 'None' if not provided
            "status" => $args['status'] ?? 'Active', // Default to 'Active' if not provided
            "marrage_date" => $args['marrage_date'] ?? null,
            "divorce_date" => $args['divorce_date'] ?? null
        ];
        
        // Check if a similar record exists based on unique constraints or business logic
        $is_exist = PersonSpouse::where('person_id', $args['person_id'])
        ->where('spouse_id', $args['spouse_id'])
        ->where('marrage_status', $args['marrage_status'] ?? 'None')
        ->where('spouse_status', $args['spouse_status'] ?? 'None')
        ->first();
        
        if ($is_exist) {
            return Error::createLocatedError("PersonSpouse-CREATE-RECORD_IS_EXIST");
        }
        $PersonSpouseResult = PersonSpouse::create($PersonSpouseModel);
        return $PersonSpouseResult;
    }
}