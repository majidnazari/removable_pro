<?php

namespace App\GraphQL\Mutations\CategoryContent;

use App\Models\CategoryContent;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\GraphQL\Enums\Status;
use App\Traits\AuthUserTrait;
use App\Exceptions\CustomValidationException;


use Log;

final class CreateCategoryContent
{
    use AuthUserTrait;
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolveCategoryContent($rootValue, array $args, GraphQLContext $context , ResolveInfo $resolveInfo)
    {

        $this->userId = $this->getUserId();

        $CategoryContentResult = [

            "title" => $args['title'],
            "status" => $args['status'] ?? status::Active,
        ];
        $is_exist = CategoryContent::where('title', $args['title'])->first();
        if ($is_exist) {
            throw new CustomValidationException("CategoryContent-CREATE-RECORD_IS_EXIST", "CategoryContent-CREATE-RECORD_IS_EXIST", 400);

            //return Error::createLocatedError("CategoryContent-CREATE-RECORD_IS_EXIST");
        }
        $CategoryContentResult_result = CategoryContent::create($CategoryContentResult);
        return $CategoryContentResult_result;
    }
}