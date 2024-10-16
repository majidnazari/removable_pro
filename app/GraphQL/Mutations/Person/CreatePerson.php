<?php

namespace App\GraphQL\Mutations\Person;

use App\Models\Person;
use GraphQL\Type\Definition\ResolveInfo;
use App\Models\GroupUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Joselfonseca\LighthouseGraphQLPassport\Persons\PasswordUpdated;
use Joselfonseca\LighthouseGraphQLPassport\Exceptions\ValidationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use Log;

final class CreatePerson
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
    public function resolvePerson($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {


        //Log::info("the args are:" . json_encode($args));
        //$user_id=auth()->guard('api')->user()->id;
        $PersonModel = [
            "creator_id" => 1,
            //"editor_id" => $args['editor_id'] ?? null,
            "node_code" => $args['node_code'] ?? "Nas_".Rand(0000000000,99999999999),
            "node_level_x" => $args['node_level_x'] ?? 0,
            "node_level_y" => $args['node_level_y'] ?? 0,
            "first_name" => $args['first_name'],
            "last_name" => $args['last_name'],
            "birth_date" => $args['birth_date'] ?? null,
            "death_date" => $args['death_date'] ?? null,
            "is_owner" => $args['is_owner'] ?? false,
            "status" => $args['status']
        ];
        
        $is_exist = Person::where('first_name' , $args['first_name'])
        ->where('last_name' , $args['last_name'])
        ->first();
        if ($is_exist) {
            return Error::createLocatedError("Person-CREATE-RECORD_IS_EXIST");
        }
        $PersonResult = Person::create($PersonModel);
        return $PersonResult;
    }
}