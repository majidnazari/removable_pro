<?php

namespace App\GraphQL\Mutations\Person;

use App\Models\Person;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\GraphQL\Enums\Status;
use Carbon\Carbon;
use App\Traits\AuthUserTrait;
use App\Traits\DuplicateCheckTrait;
use App\Exceptions\CustomValidationException;

use Log;

final class CreatePerson
{
    use AuthUserTrait;
    use DuplicateCheckTrait;

    protected $userId;
    /**
     * @param  null  $_
     * 
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolvePerson($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {

        $this->userId = $this->getUserId();

//       Log::info("the user is:" .$this->userId . "and is_owner is:" .$args['is_owner'] );

        $PersonModel = [
            "creator_id" => $this->userId,
            //"editor_id" => $args['editor_id'] ?? null,
            "node_code" => Carbon::now()->format('YmdHisv') . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT),

            "first_name" => $args['first_name'],
            "last_name" => $args['last_name'],
            "gender" => $args['gender'] ?? 0,
            "birth_date" => $args['birth_date'] ?? null,
            "death_date" => $args['death_date'] ?? null,
            "mobile" => $args['mobile'] ?? null,
            "is_owner" => $args['is_owner'] ?? false,
            "status" => $args['status'] ?? status::Active
        ];
        
        // $is_exist = Person::where('first_name' , $args['first_name'])
        // ->where('last_name' , $args['last_name'])
        // ->first();
        // if ($is_exist) {
        //     return Error::createLocatedError("Person-CREATE-RECORD_IS_EXIST");
        // }
        $this->checkDuplicate(new Person(),  $PersonModel);
        $PersonResult = Person::create($PersonModel);
        return $PersonResult;
    }
}