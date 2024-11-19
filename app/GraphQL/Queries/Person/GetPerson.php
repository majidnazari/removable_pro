<?php

namespace App\GraphQL\Queries\Person;

use App\GraphQL\Enums\MergeStatus;
use App\GraphQL\Enums\RequestStatusSender;
use App\Models\Person;
use App\Models\UserMergeRequest;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;

use App\Traits\AuthUserTrait;
use App\Traits\FindOwnerTrait;



use Log;

final class GetPerson
{
   // $user_id = auth()->guard('api')->user()->id;
 use AuthUserTrait,FindOwnerTrait;
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
    function resolvePersonFatherOfFather($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {

        $owner_person=$this->findOwner();

        // Find the given person
        //$person = $this->findUser($args['id']);//Person::find($args['id']);
        $person = $this->findUser($owner_person->id);//Person::find($args['id']);

        //Log::info("the id is:" . $args['id'] . "the person is:" . json_encode($person));

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
        $owner_person=$this->findOwner();
        // Find the given person
       // $person =$this->findUser($args['id']);// Person::find($args['id']);
        $person =$this->findUser( $owner_person->id);// Person::find($args['id']);

        // If the person exists, find the root ancestor
        if ($person) {
            $rootAncestor = $person->findRootFatherOfMother();
        }
        return $rootAncestor ? Person::find($rootAncestor->id) : $person;
    }


    public function resolvePersonAncestry($_, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        // Fetch the authenticated user's ID
        $user_id = $this->getUserId();
    
        // Fetch sender and receiver records for the logged-in user
        $sender = UserMergeRequest::where('user_sender_id', $user_id)->where('request_status_sender', RequestStatusSender::Active)->first();
        $receiver = UserMergeRequest::where('user_reciver_id', $user_id)->where('status', MergeStatus::Active)->first();
    
        Log::info("The sender is: " . json_encode($sender) . " | The receiver is: " . json_encode($receiver));
    
        // Determine if the user is acting as a sender or receiver
        if ($sender) {
            // Logged-in user is the sender
            $mineNodeId = $sender->node_sender_id; // "mine" refers to the sender's node
            $relatedNodeId = $sender->node_reciver_id; // "related_node" refers to the receiver's node
        } elseif ($receiver) {
            // Logged-in user is the receiver
            $mineNodeId = $receiver->node_reciver_id; // "mine" refers to the receiver's node
            $relatedNodeId = $receiver->node_sender_id; // "related_node" refers to the sender's node
        } else {
            // No valid sender or receiver relationship found
            return null;
        }
    
        // Fetch the "mine" and "related_node" persons
        $minePerson = $this->findUser($mineNodeId);
        $relatedPerson = $this->findUser($relatedNodeId);
    
        Log::info("Mine person: " . json_encode($minePerson) . " | Related person: " . json_encode($relatedPerson));
    
        // Ensure both persons are valid
        if (!$minePerson || !$relatedPerson) {
            return null;
        }
    
        // Determine the depth
        $depth = $args['depth'] ?? 3; // Default depth is 3 if not provided
    
        // Fetch the ancestry tree for both "mine" and "related_node"
        return [
            'mine' => $minePerson->getFullBinaryAncestry($depth),
            'related_node' => $relatedPerson->getFullBinaryAncestry($depth),
        ];
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