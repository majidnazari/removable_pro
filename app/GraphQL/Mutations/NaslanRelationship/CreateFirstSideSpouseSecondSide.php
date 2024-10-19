<?php

namespace App\GraphQL\Mutations\NaslanRelationship;

use App\Models\NaslanRelationship;
use App\Models\PersonSpouse;
use GraphQL\Type\Definition\ResolveInfo;
use App\Models\GroupUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Joselfonseca\LighthouseGraphQLPassport\Events\PasswordUpdated;
use Joselfonseca\LighthouseGraphQLPassport\Exceptions\ValidationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use Log;

final class CreateFirstSideSpouseSecondSide
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolveCreateFirstSideSpouseSecondSide($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {



        if ($args['relationship_id'] == "Spouse") //it is spouse relation and should check first with second and also check inverse relation too 
        {
            $NaslanRelationModel= [
                "creator_id" =>1,
                "person_id" => $args['first_side_person_id'],
                //"relationship_id" => $args['relationship_id'] ,           
                "spouse_id" => $args['second_side_person_id'],
                "marrage_status" => $args['marrage_status'] ?? "None",
                "spouse_status" => $args['spouse_status']  ?? "None",
                "marrage_date" => $args["marrage_date"]

            ];
        } else {
            return Error::createLocatedError("Relationship-ISNOT_VALID");

        }
        $is_exist_builder = PersonSpouse::where(function ($query) use ($args) {
            $query->where('person_id', $args['first_side_person_id'])
                  ->where('spouse_id', $args['second_side_person_id']);
        })->orWhere(function ($query) use ($args) {
            $query->where('person_id', $args['second_side_person_id'])
                  ->where('spouse_id', $args['first_side_person_id']);
        });
        
        // If the specific relationship already exists, find all marriages involving the person
        if ($is_exist_builder->exists()) {
            $allMarriages = PersonSpouse::where(function ($query) use ($args) {
                // Retrieve all marriages where the first person is involved as either person_id or spouse_id
                $query->where('person_id', $args['first_side_person_id'])
                      ->orWhere('spouse_id', $args['first_side_person_id']);
            })->orWhere(function ($query) use ($args) {
                // Retrieve all marriages where the second person is involved as either person_id or spouse_id
                $query->where('person_id', $args['second_side_person_id'])
                      ->orWhere('spouse_id', $args['second_side_person_id']);
            });
        
            return $allMarriages;
        }
        
        // Create a new relationship if it doesn't exist
        $newRelationship = PersonSpouse::create($NaslanRelationModel);
        
        // After creating, retrieve all marriages involving the first person
        $allMarriages = PersonSpouse::where(function ($query) use ($args) {
            // Retrieve all marriages where the first person is involved as either person_id or spouse_id
            $query->where('person_id', $args['first_side_person_id'])
                  ->orWhere('spouse_id', $args['first_side_person_id']);
        })->orWhere(function ($query) use ($args) {
            // Retrieve all marriages where the second person is involved as either person_id or spouse_id
            $query->where('person_id', $args['second_side_person_id'])
                  ->orWhere('spouse_id', $args['second_side_person_id']);
        });
    
        
        // Return the newly created relationship along with all other marriages
        return $allMarriages;
    }
}