<?php

namespace App\GraphQL\Mutations\Score;

use App\Models\Score;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\GraphQL\Enums\Status;
use Illuminate\Support\Facades\Auth;
use App\Traits\AuthUserTrait;

use Exception;
use Log;

final class CreateScore
{
    use  AuthUserTrait;
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

        $ScoreResult=[
            "status" => $args['status'] ?? Status::Active,
            "title" => $args['title'],
        ];
        $is_exist= Score::where('title',$args['title'])->first();
        if($is_exist)
         {
                 return Error::createLocatedError("Score-CREATE-RECORD_IS_EXIST");
         }
        $ScoreResult_result=Score::create($ScoreResult);
        return $ScoreResult_result;
    }
}