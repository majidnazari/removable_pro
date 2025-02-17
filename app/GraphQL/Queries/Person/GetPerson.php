<?php

namespace App\GraphQL\Queries\Person;

use App\GraphQL\Enums\MergeStatus;
use App\GraphQL\Enums\RequestStatusReceiver;
use App\GraphQL\Enums\RequestStatusSender;
use App\Models\Person;
use App\Models\UserMergeRequest;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesUser;
use App\Traits\FindOwnerTrait;
use App\Traits\PersonAncestryWithCompleteMerge;
use App\Traits\PersonAncestryWithActiveMerge;
use Log;

final class GetPerson
{
    use AuthUserTrait;
    use AuthorizesUser;
    use FindOwnerTrait;
    use FindOwnerTrait;
    use PersonAncestryWithCompleteMerge;
    use PersonAncestryWithActiveMerge;

    private $rootAncestors = [];

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

        // Log::info("the id is:" . $args['id'] ."the peson found is :". json_encode($Person) );
        return $Person;
    }
    // function resolvePersonFatherOfFather($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    // {

    //     $owner_person = $this->findOwner($args['id']);

    //     // Find the given person
    //     $person = $this->findUser($args['id'] ??  $owner_person->id);//Person::find($args['id']);
    //    // $person = $this->findUser($owner_person->id);//Person::find($args['id']);

    //     //Log::info("the id is:" . $args['id'] . "the person is:" . json_encode($person));

    //     // If the person exists, find the root ancestor
    //     if ($person) {
    //         $rootAncestor = $person->findRootFatherOfFather();
    //     }

    //     // else {
    //     //     return null; // Return null if the person is not found
    //     // }

    //     //Log::info("resolvePersonFatherOfFather and  rootAncestor is:" . json_encode($rootAncestor));

    //     return $rootAncestor ? Person::find($rootAncestor->id) : $person;
    //     //$this->resolvePerson($rootValue,['id' => $rootAncestor->id], $context, $resolveInfo);

    //     // // Define how deep we want to load the family tree (e.g., 4 generations)
    //     // $maxDepth = 4;

    //     // // Get dynamic relationships based on depth
    //     // $dynamicRelationships = $this->buildFamilyTreeRelationships($maxDepth);

    //     // // Debugging: Log the dynamic relationships structure
    //     // Log::info("Dynamic relationships: " . json_encode($dynamicRelationships));

    //     // // Fetch the family tree from the root ancestor with dynamically generated relationships
    //     // return Person::where('id', $rootAncestor->id)
    //     //     ->with($dynamicRelationships)
    //     //     ->first();
    // }

    // function resolvePersonFatherOfMother($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    // {
    //     $owner_person = $this->findOwner();
    //     // Find the given person
    //     // $person =$this->findUser($args['id']);// Person::find($args['id']);
    //     $person = $this->findUser($args['id'] ??  $owner_person->id);//Person::find($args['id']);

    //    // $person = $this->findUser($owner_person->id);// Person::find($args['id']);

    //     // If the person exists, find the root ancestor
    //     if ($person) {
    //         $rootAncestor = $person->findRootFatherOfMother();
    //     }
    //     return $rootAncestor ? Person::find($rootAncestor->id) : $person;
    // }


    // public function resolvePersonAncestryWithActiveMerge($_, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    // {
    //     // Fetch the authenticated user's ID
    //     $user_id = $args['id'] ?? $this->getUserId();

    //     //Log::info("the users is :" . $user_id);
    //     // Determine the depth
    //     $depth = $args['depth'] ?? 3; // Default depth is 3 if not provided

    //     // Fetch the UserMergeRequest where the user is either a sender or receiver
    //     $relation = UserMergeRequest::where(function ($query) use ($user_id) {
    //         $query->where('user_sender_id', $user_id)
    //             ->orWhere('user_receiver_id', $user_id);
    //     })
    //         ->where(function ($query) {
    //             $query->where('request_status_sender', RequestStatusSender::Active)
    //             ->where('request_status_receiver', RequestStatusReceiver::Active)
    //             ->where('status', '!=',MergeStatus::Complete);
    //                // ->orWhere('status', MergeStatus::Active);
    //         })
    //         ->first();

    //     // If no relationship is found, only return the user's own ancestry
    //     if (!$relation) {
    //         //Log::info("No relationship found for user_id: $user_id. Returning only their own family.");
    //         $minePerson = $this->findOwner($user_id);

    //         // Ensure the owner is valid
    //         if (!$minePerson) {
    //             //Log::warning("No valid owner found for user_id: $user_id.");
    //             return null;
    //         }
        

    //         [$mineAncestry,$this->rootAncestors] = $minePerson->getFullBinaryAncestry($depth);

    //         // Get the heads for the user ancestry
           
    //        // Log::info("the all heades are:" . json_encode( $this->rootAncestors));
    //         // Fetch and return only the user's own ancestry tree
    //         return [
    //             'mine' => $mineAncestry,
    //             'related_node' => null,
    //             'heads' => $this->rootAncestors
    //         ];
    //     }

    //     // Determine if the user is acting as a sender or receiver
    //     $isSender = $relation->user_sender_id === $user_id;

    //     // Assign mineUserId and relatedUserId based on the user's role
    //     $mineUserId = $isSender ? $relation->user_sender_id : $relation->user_receiver_id;
    //     $relatedUserId = $isSender ? $relation->user_receiver_id : $relation->user_sender_id;

    //     // Log the determined roles and IDs
    //     // Log::info("Role determined: " . ($isSender ? 'Sender' : 'Receiver'));
    //     //Log::info("MineUserId: $mineUserId | RelatedUserId: $relatedUserId");

    //     // Fetch the owners of both the sender and receiver nodes
    //     $minePerson = $this->findOwner($mineUserId); // Find the owner of the sender/receiver
    //     $relatedPerson = $this->findOwner($relatedUserId); // Find the owner of the related user

    //     // Log the fetched owners
    //    // Log::info("Mine person owner: " . json_encode($minePerson));
    //     //Log::info("Related person owner: " . json_encode($relatedPerson));

    //     // Ensure both owners are valid
    //     if (!$minePerson || !$relatedPerson) {
    //         //Log::warning("Invalid owner found. Mine person: " . json_encode($minePerson) . " | Related person: " . json_encode($relatedPerson));
    //         return null;
    //     }

    //     // Fetch the ancestry tree for both "mine" and "related_node"
    //     [$mineAncestry,$this->rootAncestors] = $minePerson->getFullBinaryAncestry($depth);
    //     [$relatedAncestry,$this->rootAncestors] = $relatedPerson->getFullBinaryAncestry($depth); 

    //     // Log the ancestry trees
    //     //Log::info("Mine ancestry tree: " . json_encode($mineAncestry));
    //     //Log::info("Related node ancestry tree: " . json_encode($relatedAncestry));

    //     // Return the results
    //     return [
    //         'mine' => $mineAncestry,
    //         'related_node' => $relatedAncestry,
    //         'heads' => $this->rootAncestors
    //     ];
    // }

    // public function resolvePersonAncestryWithCompleteMerge($_, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    // {
    //     // Fetch the authenticated user's ID
    //     $user_id = $args['id'] ?? $this->getUserId();
    //     $depth = $args['depth'] ?? 3; // Default depth is 3 if not provided

    //     // Fetch all UserMergeRequests with Complete status
    //     $relations = UserMergeRequest::where(function ($query) use ($user_id) {
    //         $query->where('user_sender_id', $user_id)
    //             ->orWhere('user_receiver_id', $user_id);
    //     })
    //     ->where('status', MergeStatus::Complete)
    //     ->get();

    //     // If no relationships, return only the user's own ancestry
    //     $minePerson = $this->findOwner($user_id);
    //     if (!$minePerson) {
    //         return null;
    //     }

    //     [$mineAncestry,$this->rootAncestors] = $minePerson->getFullBinaryAncestry($depth);
    //     $relatedNodes = [];

    //     foreach ($relations as $relation) {
    //         // Determine related user ID
    //         $relatedUserId = $relation->user_sender_id === $user_id
    //             ? $relation->user_receiver_id
    //             : $relation->user_sender_id;

    //         // Fetch the related person's ancestry
    //         $relatedPerson = $this->findOwner($relatedUserId);
    //         if ($relatedPerson) {
    //             [$relatedNodes[]] = $relatedPerson->getFullBinaryAncestry($depth);
    //         }
    //     }

    //     // Return as an array for `related_nodes`
    //     return [
    //         'mine' => $mineAncestry,
    //         'related_nodes' => $relatedNodes,
    //         'heads' => $this->rootAncestors
    //     ];
    // }

        

    public function findUser($id)
    {
        $person = Person::find($id);
        if ($person) {
            return $person;
        } else {
            throw new \RuntimeException("The person not found!");
            //return  Error::createLocatedError("The person not found!");
        }
    }



    public function resolvePersonAncestryWithCompleteMerge($_, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $user_id = $args['user_id'] ?? $this->getUserId();
        $depth = $args['depth'] ?? 3;

        //Log::info("the user {$user_id} and depth {$depth}");
        return $this->getPersonAncestryWithCompleteMerge($user_id, $depth);
    }

    public function resolvePersonAncestryWithActiveMerge($_, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $user_id = $args['user_id'] ?? $this->getUserId();
        $depth = $args['depth'] ?? 3;

        return $this->getPersonAncestryWithActiveMerge($user_id, $depth);
    }

}