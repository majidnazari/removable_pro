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




    // function buildFamilyTreeRelationships($depth, $currentDepth = 1, $prefix = '')
    // {
    //     // Base relationships for marriages and children at this level
    //     $relationships = [
    //         "{$prefix}PersonMarriages.Man",
    //         "{$prefix}PersonMarriages.Woman",
    //         "{$prefix}PersonMarriages.Children"
    //     ];

    //     // If we've reached the maximum depth, stop adding nested relationships
    //     if ($currentDepth >= $depth) {
    //         return $relationships;
    //     }

    //     // Add deeper levels of relationships for children’s marriages and children
    //     $nestedRelationships = $this->buildFamilyTreeRelationships($depth, $currentDepth + 1, "{$prefix}PersonMarriages.Children.");

    //     // Merge current level relationships with the nested relationships
    //     return array_merge($relationships, $nestedRelationships);
    // }




}