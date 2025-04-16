<?php

namespace App\GraphQL\Mutations\PersonScore;

use App\Models\PersonScore;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesMutation;
use App\Traits\DuplicateCheckTrait;
use App\Traits\HandlesModelUpdateAndDelete;
use App\GraphQL\Enums\AuthAction;
use App\Exceptions\CustomValidationException;

use Illuminate\Support\Facades\Auth;
use Exception;

final class UpdatePersonScore
{
    use AuthUserTrait;
    use AuthorizesMutation;
    use DuplicateCheckTrait;
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
    public function resolvePersonScore($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {  
        $this->userId = $this->getUserId();
        // $this->userAccessibility(PersonScore::class, AuthAction::Update, $args);


        // //args["user_id_creator"]=$this->userId;
        // $PersonScoreResult=PersonScore::find($args['id']);
        // $PersonScoremodel=$args;
        // $PersonScoremodel['editor_id']=$this->userId;
        
        // if(!$PersonScoreResult)
        // {
        //     return Error::createLocatedError("PersonScore-UPDATE-RECORD_NOT_FOUND");
        // }

        // $this->checkDuplicate(
        //     new PersonScore(),
        //     $args,
        //     ['id','editor_id','created_at', 'updated_at'],
        //     $args['id']
        // );


        // $args['editor_id']=$this->userId;
        // $PersonScoreResult_filled= $PersonScoreResult->fill($PersonScoremodel);
        // $PersonScoreResult->save();       
       
        // return $PersonScoreResult;

        try {

            $PersonScoreResult = $this->userAccessibility(PersonScore::class, AuthAction::Update, $args);

        } catch (CustomValidationException $e) {

            throw new CustomValidationException($e->getMessage(), $e->getMessage(), 500);

        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        $this->checkDuplicate(
            new PersonScore(),
            $args,
            ['id', 'editor_id', 'created_at', 'updated_at'],
            excludeId: $args['id']
        );

        return $this->updateModel($PersonScoreResult, $args, $this->userId);

    }
}