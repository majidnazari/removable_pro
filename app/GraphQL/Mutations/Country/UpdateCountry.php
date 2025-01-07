<?php

namespace App\GraphQL\Mutations\Country;

use App\Models\Country;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesMutation;
use App\Traits\DuplicateCheckTrait;
use App\Traits\HandlesModelUpdateAndDelete;
use App\GraphQL\Enums\AuthAction;
use Exception;

final class UpdateCountry
{
    use AuthUserTrait;
    use AuthorizesMutation;
    use DuplicateCheckTrait;
    use HandlesModelUpdateAndDelete;
    protected $userId;

    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolveCountry($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {

        $this->userId = $this->getUserId();
        //    $this->userAccessibility(Country::class, AuthAction::Delete, $args);


        //     //args["user_id_creator"]=$user_id;
        //     $CountryResult=Country::find($args['id']);

        //     if(!$CountryResult)
        //     {
        //         return Error::createLocatedError("Country-UPDATE-RECORD_NOT_FOUND");
        //     }
        //     $args['editor_id']=$this->userId;

        //     $CountryResult_filled= $CountryResult->fill($args);
        //     $CountryResult->save();       

        //     return $CountryResult;

        try {

            $CityResult = $this->userAccessibility(Country::class, AuthAction::Update, $args);

        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        $this->checkDuplicate(
            new Country(),
            $args,
            ['id', 'editor_id', 'created_at', 'updated_at'],
            excludeId: $args['id']
        );

        return $this->updateModel($CityResult, $args, $this->userId);

    }
}