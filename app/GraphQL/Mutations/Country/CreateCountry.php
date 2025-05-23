<?php

namespace App\GraphQL\Mutations\Country;

use App\Models\Country;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\Traits\AuthUserTrait;

use Log;

final class CreateCountry
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
    public function resolveCountry($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $this->userId = $this->getUserId();

        $CountryResult = [
            "title" => $args['title'],
            "code" => $args['code']
        ];
        $is_exist = Country::where('title', $args['title'])->where('code', $args['code'])->first();
        if ($is_exist) {
            return Error::createLocatedError("Country-CREATE-RECORD_IS_EXIST");
        }
        $CountryResult_result = Country::create($CountryResult);
        return $CountryResult_result;
    }
}