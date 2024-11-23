<?php

namespace App\GraphQL\Mutations\FamilyBoard;

use App\Models\FamilyBoard;
use GraphQL\Type\Definition\ResolveInfo;
use App\Models\GroupUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Joselfonseca\LighthouseGraphQLPassport\FamilyBoards\PasswordUpdated;
use Joselfonseca\LighthouseGraphQLPassport\Exceptions\ValidationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\GraphQL\Enums\Status;
use Illuminate\Support\Facades\Auth;
use Exception;

use Log;

final class CreateFamilyBoard
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
    public function resolveFamilyBoard($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {        

        //Log::info("the args are:" . json_encode($args));
        $user = Auth::guard('api')->user();

        if (!$user) {
            throw new Exception("Authentication required. No user is currently logged in.");
        }

        $this->userId = $user->id;
        $FamilyBoardResult=[
            "creator_id" =>  $this->userId,
            "category_content_id" => $args['category_content_id'],
            "title" => $args['title'],
            "selected_date" => $args['selected_date'],
            "file_path" => $args['file_path'],
            "description" => $args['description'],
            "status" => $args['status']   ?? status::Active         
        ];
        $is_exist= FamilyBoard::where('title',$args['title'])->where('status',$args['status'])->first();
        if($is_exist)
         {
                 return Error::createLocatedError("FamilyBoard-CREATE-RECORD_IS_EXIST");
         }
        $FamilyBoardResult_result=FamilyBoard::create($FamilyBoardResult);
        return $FamilyBoardResult_result;
    }
}