<?php

namespace App\GraphQL\Mutations\FamilyBoard;

use App\Models\FamilyBoard;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\GraphQL\Enums\Status;
use App\Traits\AuthUserTrait;
use App\Traits\DuplicateCheckTrait;

use Log;

final class CreateFamilyBoard
{
    use AuthUserTrait;
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
    public function resolveFamilyBoard($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {        

        $this->userId = $this->getUserId();

        $FamilyBoardResult=[
            "creator_id" =>  $this->userId,
            "category_content_id" => $args['category_content_id'],
            "group_category_id" => $args['group_category_id'] ,
            "title" => $args['title'] ,
            //"selected_date" => $args['selected_date'] ?? null,
            "content" => $args['content'] ,
            "description" => $args['description'] ?? null,
            "status" => $args['status']  ?? Status::Active         
        ];
       
         // Dynamic duplicate check: Pass column(s) and values
         $this->checkDuplicate(new FamilyBoard(), $FamilyBoardResult);

        $FamilyBoardResult_result=FamilyBoard::create($FamilyBoardResult);
        return $FamilyBoardResult_result;
    }
}