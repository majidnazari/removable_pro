<?php

namespace App\GraphQL\Mutations\Question;

use App\Models\Question;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;

final class DeleteQuestion
{
    protected $userId;

    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolveQuestion($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {  
        $user = Auth::guard('api')->user();

        if (!$user) {
            throw new Exception("Authentication required. No user is currently logged in.");
        }

        $this->userId = $user->id;;        
        $QuestionResult=Question::find($args['id']);
        
        if(!$QuestionResult)
        {
            return Error::createLocatedError("Question-DELETE-RECORD_NOT_FOUND");
        }

        $QuestionResult->editor_id= $this->userId;
        $QuestionResult->save(); 

        $QuestionResult_filled= $QuestionResult->delete();  
        return $QuestionResult;

        
    }
}