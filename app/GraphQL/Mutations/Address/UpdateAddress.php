<?php

namespace App\GraphQL\Mutations\Address;

use App\Models\Address;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesMutation;
use App\Traits\DuplicateCheckTrait;
use App\Traits\HandlesModelUpdateAndDelete;
use App\GraphQL\Enums\AuthAction;
use Exception;

use Log;


final class UpdateAddress
{
    use AuthUserTrait;
    use AuthorizesMutation;
    use DuplicateCheckTrait;
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
    public function resolveAddress($rootValue, array $args, GraphQLContext $context , ResolveInfo $resolveInfo)
    {

        $this->userId = $this->getUserId();
        //$this->userAccessibility(Address::class, AuthAction::Delete, $args);

        //args["user_id_creator"]=$user_id;
        // $AddressResult=Address::find($args['id']);
        try {

            $AddressResult = $this->userAccessibility(Address::class, AuthAction::Update, $args);

        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

        // if (!$AddressResult) {
        //     return Error::createLocatedError("Address-UPDATE-RECORD_NOT_FOUND");
        // }
        //Log::info("the address is :" . json_encode($AddressResult));
        // Log::info("the address must change into  :" . json_encode($args));

        $this->checkDuplicate(
            new Address(),
            $args,
            ['id', 'editor_id', 'created_at', 'updated_at'],
            $args['id']
        );

        return $this->updateModel($AddressResult, $args, $this->userId);
        // $args['editor_id'] = $this->userId;
        // $AddressResult_filled = $AddressResult->fill($args);
        // $AddressResult->save();

        // return $AddressResult;


    }
}