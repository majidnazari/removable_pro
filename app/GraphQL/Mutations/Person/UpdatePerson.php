<?php

namespace App\GraphQL\Mutations\Person;

use App\Models\Person;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\Rules\Person\UniquePerson;


use Log;


final class UpdatePerson
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolvePerson($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {  

       //Log::info("the args of resolver  are:" . json_encode($args));
        $user_id=auth()->guard('api')->user()->id;
        //args["user_id_creator"]=$user_id;

        $id = $args['id'];
        $firstName = $args['first_name'];
        $lastName = $args['last_name'];
        $birthDate = $args['birth_date'];

        // Instantiate the UniquePerson rule
        $uniquePersonRule = new UniquePerson($firstName, $lastName, $birthDate, $id);

        // Check if the rule passes
        if (!$uniquePersonRule->passes('first_name', $firstName)) {
            return Error::createLocatedError($uniquePersonRule->message());
        }

        $PersonResult=Person::find($args['id']);
        
        if(!$PersonResult)
        {
            return Error::createLocatedError("Person-UPDATE-RECORD_NOT_FOUND");
        }
        $args['editor_id']=$user_id;
        $PersonResult_filled= $PersonResult->fill($args);
        $PersonResult->save();       
       
        return $PersonResult;

        
    }
}