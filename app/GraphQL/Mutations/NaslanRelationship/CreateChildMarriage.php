<?php

namespace App\GraphQL\Mutations\NaslanRelationship;

use App\GraphQL\Enums\ChildKind;
use App\GraphQL\Enums\ChildStatus;
use App\Models\PersonChild;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\GraphQL\Enums\Status;
use App\Traits\AuthUserTrait;
use Log;

final class CreateChildMarriage
{
    use AuthUserTrait;
    protected $userId;

    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolveCreateChildMarriage($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {
        
         $this->userId = $this->getUserId();

        // if (($args['relationship_type'] === "Father") || ($args['relationship_type'] === "Daughter")) //it is Marriage relation and should check first with second and also check inverse relation too 
        // {
            $NaslanRelationModel= [
                "creator_id" =>$this->userId,
                "person_marriage_id" => $args['person_marriage_id'],
                //"relationship_id" => $args['relationship_id'] ,           
                "child_id" => $args['child_id'],
                "child_kind" => $args['child_kind'] ?? ChildKind::None,
                "child_status" => $args['child_status']  ?? ChildStatus::None,
                "status" => $args["status"] ?? Status::Active
    
            ];
            
        // }
        // else{
        //         //return Error::createLocatedError("Relationship-ISNOT_VALID");
        //         throw new Error("Relationship-ISNOT_VALID");
        // }
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