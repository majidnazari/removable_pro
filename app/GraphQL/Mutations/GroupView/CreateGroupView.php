<?php

namespace App\GraphQL\Mutations\GroupView;

use App\Models\GroupView;
use GraphQL\Type\Definition\ResolveInfo;
use App\Models\GroupUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Joselfonseca\LighthouseGraphQLPassport\GroupViews\PasswordUpdated;
use Joselfonseca\LighthouseGraphQLPassport\Exceptions\ValidationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\GraphQL\Enums\Status;
use Illuminate\Support\Facades\Auth;
use Exception;

use Log;

final class CreateGroupView
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolveGroupView($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    { 

        //Log::info("the args are:" . json_encode($args));
        $user = Auth::guard('api')->user();

        if (!$user) {
            throw new Exception("Authentication required. No user is currently logged in.");
        }
        $GroupViewModel=[
           
            "title" => $args['title'],          
            "status" => $args['status'] ?? Status::Active            
        ];
        $is_exist= GroupView::where($GroupViewModel)->first();
        if($is_exist)
         {
                 return Error::createLocatedError("GroupView-CREATE-RECORD_IS_EXIST");
         }
        $GroupViewResult=GroupView::create($GroupViewModel);
        return $GroupViewResult;
    }
}