<?php

namespace App\GraphQL\Queries\Person;

use App\Models\Person;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;


use Log;

final class GetPerson
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    function resolvePerson($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $Person = $this->findUser($args['id']);//Person::where('id', $args['id']);
        return $Person->first();
    }
    function resolvePersonFatherOfFather($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // Find the given person
        $person = $this->findUser($args['id']);//Person::find($args['id']);

        Log::info("the id is:" . $args['id'] . "the person is:" . json_encode($person));

        // If the person exists, find the root ancestor
        if ($person) {
            $rootAncestor = $person->findRootFatherOfFather();
        }

        // else {
        //     return null; // Return null if the person is not found
        // }

        //Log::info("resolvePersonFatherOfFather and  rootAncestor is:" . json_encode($rootAncestor));

        return $rootAncestor ? Person::find($rootAncestor->id) : $person;
        //$this->resolvePerson($rootValue,['id' => $rootAncestor->id], $context, $resolveInfo);

        // // Define how deep we want to load the family tree (e.g., 4 generations)
        // $maxDepth = 4;

        // // Get dynamic relationships based on depth
        // $dynamicRelationships = $this->buildFamilyTreeRelationships($maxDepth);

        // // Debugging: Log the dynamic relationships structure
        // Log::info("Dynamic relationships: " . json_encode($dynamicRelationships));

        // // Fetch the family tree from the root ancestor with dynamically generated relationships
        // return Person::where('id', $rootAncestor->id)
        //     ->with($dynamicRelationships)
        //     ->first();
    }

    function resolvePersonFatherOfMother($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // Find the given person
        $person =$this->findUser($args['id']);// Person::find($args['id']);

        // If the person exists, find the root ancestor
        if ($person) {
            $rootAncestor = $person->findRootFatherOfMother();
        }
        return $rootAncestor ? Person::find($rootAncestor->id) : $person;
    }


    public function resolvePersonAncestry($_, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $personId = $args['id'];
        $depth = $args['depth'] ?? 3; // Default depth of 3 if not provided

        Log::info("Fetching ancestry tree for Person ID: $personId with depth: $depth");

        // Find the person by ID
        $person = $this->findUser($personId);//Person::find($personId);
        
        if (!$person) {
            Log::info("Person with ID $personId not found.");
            return null;
        }

        // Fetch the ancestry tree
        return $person->getFullBinaryAncestry($depth);
    }

    public function findUser($id)
    {
        $person = Person::find($id);
        if($person)
        {
            return $person;
        }
        else
        {
            throw new \RuntimeException("The person not found!");
            //return  Error::createLocatedError("The person not found!");
        }
    }

}