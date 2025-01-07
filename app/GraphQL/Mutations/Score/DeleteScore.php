<?php

namespace App\GraphQL\Mutations\Score;

use App\Models\Score;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use Illuminate\Support\Facades\Auth;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesMutation;
use App\Traits\HandlesModelUpdateAndDelete;
use App\GraphQL\Enums\AuthAction;

use Exception;
final class DeleteScore
{
    use AuthUserTrait;
    use AuthorizesMutation;
    use HandlesModelUpdateAndDelete;

    protected $userId;

    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolveScore($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {
        $this->userId = $this->getUserId();
        //    $this->userAccessibility(Score::class, AuthAction::Delete, $args);

        //     $ScoreResult=Score::find($args['id']);

        //     if(!$ScoreResult)
        //     {
        //         return Error::createLocatedError("Score-DELETE-RECORD_NOT_FOUND");
        //     }
        //     $ScoreResult->editor_id= $this->userId;
        //     $ScoreResult->save(); 


        //     $ScoreResult_filled= $ScoreResult->delete();  
        //     return $ScoreResult;
        try {

            $ScoreResult = $this->userAccessibility(Score::class, AuthAction::Delete, $args);

        } catch (Exception $e) {
            throw new Exception($e->getMessage());

        }

        return $this->updateAndDeleteModel($ScoreResult, $args, $this->userId);



    }
}