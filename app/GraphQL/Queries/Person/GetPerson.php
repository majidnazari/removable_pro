<?php

namespace App\GraphQL\Queries\Person;

use App\Models\Person;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

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
        $Person = Person::where('id', $args['id']);
        return $Person->first();
    }
    function resolvePersonWithAncestry($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // Find the given person
        $person = Person::find($args['id']);

        // If the person exists, find the root ancestor
        if ($person) {
            $rootAncestor = $person->findRootAncestor();
        }

        // else {
        //     return null; // Return null if the person is not found
        // }

        //Log::info("resolvePersonWithAncestry and  rootAncestor is:" . json_encode($rootAncestor));

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



    public function resolvePersonAncestry($_, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $personId = $args['id'];
        $depth = $args['depth'] ?? 3; // Default depth of 3 if not provided

        Log::info("Fetching ancestry tree for Person ID: $personId with depth: $depth");

        // Find the person by ID
        $person = Person::find($personId);
        
        if (!$person) {
            Log::info("Person with ID $personId not found.");
            return null;
        }

        // Fetch the ancestry tree
        return $person->getFullBinaryAncestry($depth);
    }

}