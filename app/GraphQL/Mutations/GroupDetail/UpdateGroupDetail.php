<?php

namespace App\GraphQL\Mutations\GroupDetail;

use App\Models\GroupDetail;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesMutation;
use App\Traits\DuplicateCheckTrait;
use App\GraphQL\Enums\AuthAction;



final class UpdateGroupDetail
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
    public function resolveGroupDetail($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {  
        $this->userId = $this->getUserId();
        $this->userAccessibility(GroupDetail::class, AuthAction::Update, $args);


        //args["user_id_creator"]=$user_id;
        $GroupDetailResult=GroupDetail::find($args['id']);
        
        if(!$GroupDetailResult)
        {
            return Error::createLocatedError("GroupDetail-UPDATE-RECORD_NOT_FOUND");
        }
        $this->checkDuplicate(
            new GroupDetail(),
            $args,
            ['id','editor_id','created_at', 'updated_at'],
            $args['id']
        );
        $args['editor_id']= $this->userId;
        $GroupDetailResult_filled= $GroupDetailResult->fill($args);
        $GroupDetailResult->save();       
       
        return $GroupDetailResult;

        
    }
}