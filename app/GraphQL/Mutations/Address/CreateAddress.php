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
    public function resolveAddress($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {        
        
       // Log::info("the status is:". $statusValue );
       // Log::info("the status is:". $statusValue );

        $AddressResult=[
            "creator_id"=>  $this->getUserId(),
            "person_id"=> $args['person_id'],
            "country_id"=> $args['country_id'],
            "province_id"=> $args['province_id'],
            "city_id"=> $args['city_id'],
            "location_title" => $args['location_title'],
            "street_name" => $args['street_name'] ?? null,
            "builder_no" => $args['builder_no'] ?? null,
            "floor_no" => $args['floor_no'] ?? null,
            "unit_no" => $args['unit_no'] ?? null,
            "lat" => $args['lat'] ?? null,
            "lon" => $args['lon'] ?? null,
            "status" =>  $args['status'] ?? status::Active,
                
        ];
        // $is_exist= Address::where($AddressResult)->first();
        // if($is_exist)
        //  {
        //          return Error::createLocatedError("Address-CREATE-RECORD_IS_EXIST");
        //  }
        $this->checkDuplicate(new Address(),  $AddressResult);
        $AddressResult_result=Address::create($AddressResult);
        return $AddressResult_result;
    }
}