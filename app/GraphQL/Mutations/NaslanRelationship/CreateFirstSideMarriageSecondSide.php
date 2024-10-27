<?php

namespace App\GraphQL\Mutations\NaslanRelationship;

use App\Models\NaslanRelationship;
use App\Models\PersonMarriage;
use GraphQL\Type\Definition\ResolveInfo;
use App\Models\GroupUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Joselfonseca\LighthouseGraphQLPassport\Events\PasswordUpdated;
use Joselfonseca\LighthouseGraphQLPassport\Exceptions\ValidationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use Log;

final class CreateFirstSideMarriageSecondSide
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolveCreateFirstSideMarriageSecondSide($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {



        if ($args['relationship_id'] === "Marriage") //it is Marriage relation and should check first with second and also check inverse relation too 
        {
            $NaslanRelationModel= [
                "creator_id" =>1,
                "man_id" => $args['first_side_person_id'],
                //"relationship_id" => $args['relationship_id'] ,           
                "woman_id" => $args['second_side_person_id'],
                "Marriage_status" => $args['Marriage_status']  ?? "None",
                "marriage_date" => $args["marriage_date"]

            ];
        } else {
            return Error::createLocatedError("Relationship-ISNOT_VALID");

        }
        $is_exist_builder = PersonMarriage::where(function ($query) use ($args) {
            $query->where('person_id', $args['first_side_person_id'])
                  ->where('Marriage_id', $args['second_side_person_id']);
        })->orWhere(function ($query) use ($args) {
            $query->where('person_id', $args['second_side_person_id'])
                  ->where('Marriage_id', $args['first_side_person_id']);
        });
        
        // If the specific relationship already exists, find all marriages involving the person
        if ($is_exist_builder->exists()) {
            $allMarriages = PersonMarriage::where(function ($query) use ($args) {
                // Retrieve all marriages where the first person is involved as either person_id or Marriage_id
                $query->where('person_id', $args['first_side_person_id'])
                      ->orWhere('Marriage_id', $args['first_side_person_id']);
            })->orWhere(function ($query) use ($args) {
                // Retrieve all marriages where the second person is involved as either person_id or Marriage_id
                $query->where('person_id', $args['second_side_person_id'])
                      ->orWhere('Marriage_id', $args['second_side_person_id']);
            });
        
            return $allMarriages;
        }
        
        // Create a new relationship if it doesn't exist
        $newRelationship = PersonMarriage::create($NaslanRelationModel);
        
        // After creating, retrieve all marriages involving the first person
        $allMarriages = PersonMarriage::where(function ($query) use ($args) {
            // Retrieve all marriages where the first person is involved as either person_id or Marriage_id
            $query->where('person_id', $args['first_side_person_id'])
                  ->orWhere('Marriage_id', $args['first_side_person_id']);
        })->orWhere(function ($query) use ($args) {
            // Retrieve all marriages where the second person is involved as either person_id or Marriage_id
            $query->where('person_id', $args['second_side_person_id'])
                  ->orWhere('Marriage_id', $args['second_side_person_id']);
        });
    
        
        // Return the newly created relationship along with all other marriages
        return $allMarriages;
    }
}