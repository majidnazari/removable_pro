<?php

namespace App\GraphQL\Mutations\City;

use App\Models\City;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesMutation;
use App\Traits\HandlesModelUpdateAndDelete;
use App\Traits\DuplicateCheckTrait;
use App\GraphQL\Enums\AuthAction;
use Exception;

final class UpdateCity
{
    use AuthUserTrait;
    use AuthorizesMutation;
    use DuplicateCheckTrait;
    use HandlesModelUpdateAndDelete;
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolveCity($rootValue, array $args, GraphQLContext $context , ResolveInfo $resolveInfo)
    {

        $this->userId = $this->getUserId();

        try {

            $CityResult = $this->userAccessibility(City::class, AuthAction::Update, $args);

        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        $this->checkDuplicate(
            new City(),
            $args,
            ['id', 'editor_id', 'created_at', 'updated_at'],
            excludeId: $args['id']
        );

        return $this->updateModel($CityResult, $args, $this->userId);
        //    $this->userAccessibility(City::class, AuthAction::Delete, $args);


        //     //args["user_id_creator"]=$user_id;
        //     $CityResult=City::find($args['id']);

        //     if(!$CityResult)
        //     {
        //         return Error::createLocatedError("City-UPDATE-RECORD_NOT_FOUND");
        //     }
        //     $CityResult_filled= $CityResult->fill($args);
        //     $CityResult->save();       

        //     return $CityResult;


    }
}