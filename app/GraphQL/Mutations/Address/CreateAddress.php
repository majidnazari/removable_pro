<?php

namespace App\GraphQL\Mutations\Address;

use App\Models\Address;
use GraphQL\Type\Definition\ResolveInfo;
use App\Models\GroupUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Joselfonseca\LighthouseGraphQLPassport\Events\PasswordUpdated;
use Joselfonseca\LighthouseGraphQLPassport\Exceptions\ValidationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use Illuminate\Support\Facades\Auth;
use Exception;

use App\GraphQL\Enums\Status;


use Log;

final class CreateAddress
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
    public function resolveAddress($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {        
        
        $user = Auth::guard('api')->user();

        if (!$user) {
            throw new Exception("Authentication required. No user is currently logged in.");
        }

        $this->userId = $user->id;

           // Use the helper to get the integer value of status
        //$statusValue = StatusHelper::getStatusValue($args['status']);

       // Log::info("the status is:". $statusValue );

        $AddressResult=[
            "creator_id"=>  $this->userId,
            "person_id"=> $args['person_id'],
            "country_id"=> $args['country_id'],
            "province_id"=> $args['province_id'],
            "city_id"=> $args['city_id'],
            "area_id" => $args['area_id'],
            "location_title" => $args['location_title'],
            "street_name" => $args['street_name'],
            "builder_no" => $args['builder_no'],
            "floor_no" => $args['floor_no'],
            "unit_no" => $args['unit_no'],
            "status" =>  $args['status'] ?? status::Active,
                
        ];
        $is_exist= Address::where($AddressResult)->first();
        if($is_exist)
         {
                 return Error::createLocatedError("Address-CREATE-RECORD_IS_EXIST");
         }
        $AddressResult_result=Address::create($AddressResult);
        return $AddressResult_result;
    }
}