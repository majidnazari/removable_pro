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

        // Get the owner person and related node ID
        $owner_person = $this->findOwner(); // Primary person
        $personId = $owner_person->id;

        $depth = $args['depth'] ?? 3; // Default depth is 3 if not provided

        $data=[
            "user_sender_id" => $user_id,
            "node_sender_id" => $personId,
            "request_status_sender" =>RequestStatusSender::Active,
            "status" => MergeStatus::Active,
        ];

        $userRequestMerge = UserMergeRequest::where( 'user_sender_id', $user_id)->orWhere('user_reciver_id', $user_id)->first();


        Log::info("the data are:" . json_encode($data) . " userRequestMerge is:" . json_encode( $userRequestMerge ));

        $relatedNodeId = $userRequestMerge ? $userRequestMerge->node_reciver_id : null;

        //$relatedNodeId = 8;//$args['relatedNodeId'] ?? null; // ID of the related node

        // Fetch the main person
        $person = $this->findUser($personId); // Person::find($personId);
        $related_person = $this->findUser($relatedNodeId); // Person::find($personId);

        // Return null if no primary person is found
        if (!$person) {
            return null;
        }

        // Fetch the related node person, if provided
       // $relatedNodePerson = $relatedNodeId ? $this->findUser($relatedNodeId) : null;

        // Fetch the ancestry tree for both main and related nodes
        return [
            'mine' => $person->getFullBinaryAncestry($depth),
            'related_node' =>$related_person->getFullBinaryAncestry($depth) ,
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