<?php

namespace App\GraphQL\Mutations\Province;

use App\Models\Province;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use Illuminate\Support\Facades\Auth;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesMutation;
use App\Traits\DuplicateCheckTrait;
use App\Traits\HandlesModelUpdateAndDelete;
use App\GraphQL\Enums\AuthAction;


use Exception;

final class UpdateProvince
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
    public function resolveProvince($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {

        $this->userId = $this->getUserId();
        // $this->userAccessibility(Province::class, AuthAction::Delete, $args);

        // //args["user_id_creator"]=$this->userId;
        // $ProvinceResult=Province::find($args['id']);

        // if(!$ProvinceResult)
        // {
        //     return Error::createLocatedError("Province-UPDATE-RECORD_NOT_FOUND");
        // }

        // $this->checkDuplicate(
        //     new Province(),
        //     $args,
        //     ['id','editor_id','created_at', 'updated_at'],
        //     $args['id']
        // );
        // $args['editor_id']=$this->userId;

        // $ProvinceResult_filled= $ProvinceResult->fill($args);
        // $ProvinceResult->save();       

        // return $ProvinceResult;
        try {

            $ProvinceResult = $this->userAccessibility(Province::class, AuthAction::Update, $args);

        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        $this->checkDuplicate(
            new Province(),
            $args,
            ['id', 'editor_id', 'created_at', 'updated_at'],
            excludeId: $args['id']
        );

        return $this->updateModel($ProvinceResult, $args, $this->userId);



    }
}