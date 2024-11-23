<?php

namespace App\GraphQL\Mutations\PersonChild;

use App\GraphQL\Enums\ChildKind;
use App\GraphQL\Enums\ChildStatus;
use App\Models\PersonChild;
use GraphQL\Type\Definition\ResolveInfo;
use App\Models\GroupUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Joselfonseca\LighthouseGraphQLPassport\PersonChilds\PasswordUpdated;
use Joselfonseca\LighthouseGraphQLPassport\Exceptions\ValidationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\GraphQL\Enums\Status;
use Illuminate\Support\Facades\Auth;
use Exception;
use Log;

final class CreatePersonChild
{
    protected $userId;

   
    /**
     * @param  null  $_
     * 
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolvePersonChild($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {



        //Log::info("the args are:" . json_encode($args));
        $user = Auth::guard('api')->user();

        if (!$user) {
            throw new Exception("Authentication required. No user is currently logged in.");
        }

        $this->userId = $user->id;;
        $PersonChildModel = [
            "creator_id" =>  $this->userId,
            "editor_id" => $args['editor_id'] ?? null,
            "person_marriage_id" => $args['person_marriage_id'] ,
            "child_id" => $args['child_id'],
            "child_kind" => $args['child_kind'] ?? ChildKind::DirectChild, // Default to 'Direct_child' if not provided
            "child_status" => $args['child_status'] ?? ChildStatus::WithFamily, // Default to 'With_family' if not provided
            "status" => $args['status'] ?? status::Active // Default to 'Active' if not provided
        ];
        
        $is_exist = PersonChild::where('person_marriage_id' , $args['person_marriage_id'])
        ->where('child_id' , $args['child_id'])
            ->first();
        if ($is_exist) {
            return Error::createLocatedError("PersonChild-CREATE-RECORD_IS_EXIST");
        }
        $PersonChildResult = PersonChild::create($PersonChildModel);
        return $PersonChildResult;
    }
}