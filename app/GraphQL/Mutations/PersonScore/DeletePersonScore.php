<?php

namespace App\GraphQL\Mutations\PersonScore;

use App\Models\PersonScore;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use Illuminate\Support\Facades\Auth;
use App\Traits\AuthUserTrait;

use Exception;
final class DeletePersonScore
{
    use AuthUserTrait;
    protected $userId;

    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolvePersonScore($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {  
        $this->userId = $this->getUserId();
      
        $PersonScoreResult=PersonScore::find($args['id']);
        
        if(!$PersonScoreResult)
        {
            return Error::createLocatedError("PersonScore-DELETE-RECORD_NOT_FOUND");
        }

        $PersonScoreResult->editor_id= $this->userId;
        $PersonScoreResult->save(); 

        $PersonScoreResult_filled= $PersonScoreResult->delete();  
        return $PersonScoreResult;

        
    }
}