<?php

namespace App\GraphQL\Mutations\Memory;

use App\Models\Memory;
use GraphQL\Type\Definition\ResolveInfo;
use App\Models\GroupUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Joselfonseca\LighthouseGraphQLPassport\Memorys\PasswordUpdated;
use Joselfonseca\LighthouseGraphQLPassport\Exceptions\ValidationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use Log;

final class CreateMemory
{
    /**
     * @param  null  $_
     * 
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolveMemory($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {


        //Log::info("the args are:" . json_encode($args));
        //$user_id=auth()->guard('api')->user()->id;
        $MemoryModel = [
            "creator_id" => 1,
            "person_id" => $args['person_id'],
            "category_content_id" => $args['category_content_id'],
            "group_view_id" => $args['group_view_id'],
            "content" => $args['content'],
            "title" => $args['title'],
            "description" => $args['description'],
            "is_shown_after_death" => $args['is_shown_after_death'],
            "status" => $args['status']
        ];
        $is_exist = Memory::where($MemoryModel)->first();
        if ($is_exist) {
            return Error::createLocatedError("Memory-CREATE-RECORD_IS_EXIST");
        }
        $MemoryResult = Memory::create($MemoryModel);
        return $MemoryResult;
    }
}