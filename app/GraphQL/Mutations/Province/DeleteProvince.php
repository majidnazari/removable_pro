<?php

namespace App\GraphQL\Mutations\Province;

use App\Models\Province;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use Illuminate\Support\Facades\Auth;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesMutation;
use App\Traits\HandlesModelUpdateAndDelete;
use App\GraphQL\Enums\AuthAction;


use Exception;
final class DeleteProvince
{
    use AuthUserTrait;
    use AuthorizesMutation;
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
    public function resolveProvince($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {
        $this->userId = $this->getUserId();
        //    $this->userAccessibility(Score::class, AuthAction::Delete, $args);


        //     $ProvinceResult=Province::find($args['id']);

        //     if(!$ProvinceResult)
        //     {
        //         return Error::createLocatedError("Province-DELETE-RECORD_NOT_FOUND");
        //     }

        //     $ProvinceResult->editor_id= $this->userId;
        //     $ProvinceResult->save(); 


        //     $ProvinceResult_filled= $ProvinceResult->delete();  
        //     return $ProvinceResult;


        try {

            $ProvinceResult = $this->userAccessibility(Province::class, AuthAction::Delete, $args);

        } catch (Exception $e) {
            throw new Exception($e->getMessage());

        }

        return $this->updateAndDeleteModel($ProvinceResult, $args, $this->userId);


    }
}