<?php

namespace App\GraphQL\Mutations\PersonChild;

use App\Models\PersonChild;
use GraphQL\Type\Definition\ResolveInfo;
use App\Models\GroupUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Joselfonseca\LighthouseGraphQLPassport\PersonChilds\PasswordUpdated;
use Joselfonseca\LighthouseGraphQLPassport\Exceptions\ValidationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use Log;

final class CreatePersonChild
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
    public function resolvePersonChild($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {



        //Log::info("the args are:" . json_encode($args));
        //$user_id=auth()->guard('api')->user()->id;
        $PersonChildModel = [
            "creator_id" => 1,
            "editor_id" => $args['editor_id'] ?? null,
            "person_spouse_id" => $args['person_spouse_id'] ,
            "child_id" => $args['child_id'],
            "child_kind" => $args['child_kind'] ?? 'Direct_child', // Default to 'Direct_child' if not provided
            "child_status" => $args['child_status'] ?? 'With_family', // Default to 'With_family' if not provided
            "status" => $args['status'] ?? 'Active' // Default to 'Active' if not provided
        ];
        
        $is_exist = PersonChild::where('person_spouse_id' , $args['person_spouse_id'])
        ->where('child_id' , $args['child_id'])
            ->first();
        if ($is_exist) {
            return Error::createLocatedError("PersonChild-CREATE-RECORD_IS_EXIST");
        }
        $PersonChildResult = PersonChild::create($PersonChildModel);
        return $PersonChildResult;
    }
}