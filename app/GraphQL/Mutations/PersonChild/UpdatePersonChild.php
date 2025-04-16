<?php

namespace App\GraphQL\Mutations\PersonChild;

use App\Models\PersonChild;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use Illuminate\Support\Facades\Auth;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesMutation;
use App\Traits\DuplicateCheckTrait;
use App\Traits\HandlesModelUpdateAndDelete;
use App\GraphQL\Enums\AuthAction;
use App\Exceptions\CustomValidationException;

use Exception;

final class UpdatePersonChild
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
    public function resolvePersonChild($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {  
        $this->userId = $this->getUserId();
        // $this->userAccessibility(PersonChild::class, AuthAction::Update, $args);


        // //args["user_id_creator"]=$this->userId;
        // $PersonChildResult=PersonChild::find($args['id']);
        // $personChildmodel=$args;
        // $personChildmodel['editor_id']=$this->userId;
        
        // if(!$PersonChildResult)
        // {
        //     return Error::createLocatedError("PersonChild-UPDATE-RECORD_NOT_FOUND");
        // }
        // $this->checkDuplicate(
        //     new PersonChild(),
        //     $args,
        //     ['id','editor_id','created_at', 'updated_at'],
        //     $args['id']
        // );
        // $args['editor_id']=$this->userId;
        // $PersonChildResult_filled= $PersonChildResult->fill($personChildmodel);
        // $PersonChildResult->save();       
       
        // return $PersonChildResult;

        try {

            $GroupDetailResult = $this->userAccessibility(PersonChild::class, AuthAction::Update, $args);

        } catch (CustomValidationException $e) {

            throw new CustomValidationException($e->getMessage(), $e->getMessage(), 500);

        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        $this->checkDuplicate(
            new PersonChild(),
            $args,
            ['id', 'editor_id', 'created_at', 'updated_at'],
            excludeId: $args['id']
        );

        return $this->updateModel($GroupDetailResult, $args, $this->userId);


        
    }
}