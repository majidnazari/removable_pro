<?php

namespace App\GraphQL\Mutations\CategoryContent;

use App\Models\CategoryContent;
use GraphQL\Type\Definition\ResolveInfo;
use App\Models\GroupUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Joselfonseca\LighthouseGraphQLPassport\Events\PasswordUpdated;
use Joselfonseca\LighthouseGraphQLPassport\Exceptions\ValidationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\GraphQL\Enums\Status;
use Illuminate\Support\Facades\Auth;
use Exception;

use Log;

final class CreateCategoryContent
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolveCategoryContent($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {

        //Log::info("the args are:" . json_encode($args));
        $user = Auth::guard('api')->user();

        if (!$user) {
            throw new Exception("Authentication required. No user is currently logged in.");
        }

        $this->userId = $user->id;
        $CategoryContentResult = [

            "title" => $args['title'],
            "status" => $args['status'] ?? status::Active,
        ];
        $is_exist = CategoryContent::where('title', $args['title'])->first();
        if ($is_exist) {
            return Error::createLocatedError("CategoryContent-CREATE-RECORD_IS_EXIST");
        }
        $CategoryContentResult_result = CategoryContent::create($CategoryContentResult);
        return $CategoryContentResult_result;
    }
}