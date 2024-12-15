<?php

namespace App\GraphQL\Mutations\PersonMarriage;

use App\Models\PersonMarriage;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use Illuminate\Support\Facades\Auth;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesMutation;
use App\Traits\DuplicateCheckTrait;
use App\GraphQL\Enums\AuthAction;

use Exception;

final class UpdatePersonMarriage
{
    use AuthUserTrait;
    use AuthorizesMutation;
    use DuplicateCheckTrait;

    protected $userId;

    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolvePersonMarriage($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {  
       
        $this->userId = $this->getUserId();
       $this->userAccessibility(PersonMarriage::class, AuthAction::Update, $args);


        //args["user_id_creator"]=$this->userId;
        $PersonMarriageResult=PersonMarriage::find($args['id']);
        $PersonMarriagemodel=$args;
        $PersonMarriagemodel['editor_id']=$this->userId;
        
        if(!$PersonMarriageResult)
        {
            return Error::createLocatedError("PersonMarriage-UPDATE-RECORD_NOT_FOUND");
        }
        $this->checkDuplicate(
            new PersonMarriage(),
            $args,
            ['id','editor_id','created_at', 'updated_at'],
            $args['id']
        );
        $args['editor_id']=$this->userId;
        $PersonMarriageResult_filled= $PersonMarriageResult->fill($PersonMarriagemodel);
        $PersonMarriageResult->save();       
       
        return $PersonMarriageResult;

        
    }
}