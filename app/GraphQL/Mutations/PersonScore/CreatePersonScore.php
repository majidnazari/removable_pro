<?php

namespace App\GraphQL\Mutations\PersonScore;

use App\Models\PersonScore;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\GraphQL\Enums\Status;
use Illuminate\Support\Facades\Auth;
use App\Traits\AuthUserTrait;
use App\Traits\DuplicateCheckTrait;

use Exception;
use Log;

final class CreatePersonScore
{
    use AuthUserTrait;
    use DuplicateCheckTrait;

    protected $userId;
   
    /**
     * @param  null  $_
     * 
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolvePersonScore($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $this->userId = $this->getUserId();

        $PersonScoreModel = [
            "creator_id" =>  $this->userId,
            "person_id" => $args['person_id'],
            "score_id" => $args['score_id'],
            
            "score_level" => $args['score_level'] ,
            "status" => $args['status'] ?? Status::Active, // Default to 'None' if not provided
        ];
        
        // Check if a similar details profile already exists for the same person_id
        // $is_exist = PersonScore::where('person_id', $args['person_id'])->first();
        
        // if ($is_exist) {
        //     return Error::createLocatedError("PersonScore-CREATE-RECORD_IS_EXIST");
        // }

        $this->checkDuplicate(
            new PersonScore(),
            $PersonScoreModel
        );
        $PersonScoreResult = PersonScore::create($PersonScoreModel);
        return $PersonScoreResult;
    }
}