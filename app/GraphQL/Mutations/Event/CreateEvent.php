<?php

namespace App\GraphQL\Mutations\Event;

use App\Models\Event;
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

final class CreateEvent
{
    protected $userId;

    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolveEvent($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {        

        //Log::info("the args are:" . json_encode($args));
        $user = Auth::guard('api')->user();

        if (!$user) {
            throw new Exception("Authentication required. No user is currently logged in.");
        }

        $this->userId = $user->id;
        $EventResult=[
            "creator_id" =>  $this->userId,
            "title" => $args['title'],
            "status" => $args['status'] ?? Status::Active,           
        ];
        $is_exist= Event::where('title',$args['title'])->where('status',$args['status'])->first();
        if($is_exist)
         {
                 return Error::createLocatedError("Event-CREATE-RECORD_IS_EXIST");
         }
        $EventResult_result=Event::create($EventResult);
        return $EventResult_result;
    }
}