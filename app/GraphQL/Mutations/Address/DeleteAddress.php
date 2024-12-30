<?php

namespace App\GraphQL\Mutations\Address;

use App\Models\Address;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesMutation;
use App\Traits\HandlesModelUpdateAndDelete;
use App\GraphQL\Enums\AuthAction;
use Exception;



final class DeleteAddress
{
    use AuthUserTrait;
    use AuthorizesMutation;
    use HandlesModelUpdateAndDelete;
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    protected $userId;
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolveAddress($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {

        $this->userId = $this->getUserId();
        //  $this->userAccessibility(Address::class, AuthAction::Delete, $args);

        try {

            $AddressResult = $this->userAccessibility(Address::class, AuthAction::Delete, $args);

        } catch (Exception $e) {
            throw new Exception($e->getMessage());

        }

        return $this->updateAndDeleteModel($AddressResult, $args, $this->userId);
        //$AddressResult = Address::find($args['id']);

        // if(!$AddressResult)
        // {
        //     return Error::createLocatedError("Address-DELETE-RECORD_NOT_FOUND");
        // }
        // $AddressResult->editor_id = $this->userId;
        // $AddressResult->save();
        // $AddressResult_filled = $AddressResult->delete();
        // return $AddressResult;


    }
}