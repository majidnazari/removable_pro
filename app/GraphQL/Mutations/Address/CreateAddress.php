<?php

namespace App\GraphQL\Mutations\Address;

use App\Models\Address;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\Traits\AuthUserTrait;
use App\Traits\DuplicateCheckTrait;
use Illuminate\Support\Facades\Auth;

use App\GraphQL\Enums\Status;
use Log;

final class CreateAddress
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
    public function resolveAddress($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {

        $AddressResult = [
            "creator_id" => $this->getUserId(),
            "person_id" => $args['person_id'],
            "country_id" => $args['country_id'] ?? null,
            "province_id" => $args['province_id'] ?? null,
            "city_id" => $args['city_id'] ?? null,
            "location_title" => $args['location_title'],
            "street_name" => $args['street_name'] ?? null,
            "builder_no" => $args['builder_no'] ?? null,
            "floor_no" => $args['floor_no'] ?? null,
            "unit_no" => $args['unit_no'] ?? null,
            "lat" => $args['lat'] ?? null,
            "lon" => $args['lon'] ?? null,
            "status" => $args['status'] ?? status::Active,

        ];

        $this->checkDuplicate(new Address(), ["creator_id" => $this->getUserId(), "person_id" => $args['person_id']]);
        $AddressResult_result = Address::create($AddressResult);
        return $AddressResult_result;
    }
}