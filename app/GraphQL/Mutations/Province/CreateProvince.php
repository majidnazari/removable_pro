<?php

namespace App\GraphQL\Mutations\Province;

use App\Models\Province;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use Illuminate\Support\Facades\Auth;
use App\Traits\AuthUserTrait;

use Exception;

final class CreateProvince
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
    public function resolveProvince($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {        
        $this->userId = $this->getUserId();

        $ProvinceResult=[
            "country_id" => $args['country_id'],
            "title" => $args['title'],
            "code" => $args['code']            
        ];
        $is_exist= Province::where('title',$args['title'])->where('code',$args['code'])->first();
        if($is_exist)
         {
                 return Error::createLocatedError("Province-CREATE-RECORD_IS_EXIST");
         }
        $ProvinceResult_result=Province::create($ProvinceResult);
        return $ProvinceResult_result;
    }
}