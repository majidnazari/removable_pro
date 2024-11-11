<?php

namespace App\GraphQL\Mutations\NaslanRelationship;

use App\GraphQL\Enums\ChildKind;
use App\GraphQL\Enums\ChildStatus;
use App\GraphQL\Enums\Status;
use App\Models\NaslanRelationship;
use App\Models\PersonChild;
use GraphQL\Type\Definition\ResolveInfo;
use App\Models\GroupUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Joselfonseca\LighthouseGraphQLPassport\Events\PasswordUpdated;
use Joselfonseca\LighthouseGraphQLPassport\Exceptions\ValidationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use Log;

final class CreateMarriageChild
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolveCreateMarriageChild($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {
        $user_id=auth()->guard('api')->user()->id;


        if (($args['relationship_type'] === "Son") || ($args['relationship_type'] === "Daughter")) //it is Marriage relation and should check first with second and also check inverse relation too 
        {
            $NaslanRelationModel= [
                "creator_id" => $user_id,
                "person_marriage_id" => $args['person_marriage_id'],
                //"relationship_id" => $args['relationship_id'] ,           
                "child_id" => $args['child_id'],
                "child_kind" => $args['child_kind'] ?? ChildKind::None,
                "child_status" => $args['child_status']  ?? ChildStatus::None,
                "status" => $args["status"] ?? Status::Active
    
            ];
            
        }
        else{
                //return Error::createLocatedError("Relationship-ISNOT_VALID");
                throw new Error("Relationship-ISNOT_VALID");
        }
       // return PersonChild::where('deleted_at',null);
      
        $is_exist_builder = PersonChild::where('person_marriage_id', $args['person_marriage_id'])->where('child_id', $args['child_id']);    

        // If the specific relationship already exists, find all marriages involving the person
        if ($is_exist_builder->exists()) {
            $allchildrens = PersonChild::where('person_marriage_id', $args['person_marriage_id']);
            return $allchildrens;
        }

        // Create a new relationship if it doesn't exist
        $newRelationship = PersonChild::create($NaslanRelationModel);

        $allchildrens = PersonChild::where('person_marriage_id', $args['person_marriage_id']);
        return $allchildrens;
    }
}