<?php

namespace App\GraphQL\Mutations\Event;

use App\Models\Event;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\GraphQL\Enums\Status;
use App\Traits\AuthUserTrait;

use Log;

final class CreateEvent
{
    use AuthUserTrait;
    protected $userId;

    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolveEvent($rootValue, array $args, GraphQLContext $context , ResolveInfo $resolveInfo)
    {
        $this->userId = $this->getUserId();

        $EventResult = [
            "creator_id" => $this->userId,
            "title" => trim($args['title']),
            "status" => $args['status'] ?? Status::Active,
        ];
        $is_exist = Event::where('title', $args['title'])->where('status', $args['status'])->first();
        if ($is_exist) {
            return Error::createLocatedError("Event-CREATE-RECORD_IS_EXIST");
        }
        $EventResult_result = Event::create($EventResult);
        return $EventResult_result;
    }
}