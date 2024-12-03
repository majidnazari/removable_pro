<?php

namespace App\GraphQL\Mutations\PersonDetails;

use App\Models\PersonDetail;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use Illuminate\Support\Facades\Auth;
use App\Traits\AuthUserTrait;
use App\Traits\checkMutationAuthorization;
use App\GraphQL\Enums\AuthAction;
use Exception;
use Log;

final class DeletePersonDetails
{
    use AuthUserTrait;
    use checkMutationAuthorization;
    protected $userId;

    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolvePersonDetail($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {  
       
        $this->userId = $this->getUserId();
        $this->checkMutationAuthorization(PersonDetail::class, AuthAction::Delete, $args);

      
        $PersonDetailResult=PersonDetail::find($args['id']);
        
        if(!$PersonDetailResult)
        {
            return Error::createLocatedError("PersonDetail-DELETE-RECORD_NOT_FOUND");
        }

        if ($PersonDetailResult && $PersonDetailResult['profile_picture'] !=null ) {
            $oldImagePath = public_path('storage/profile_pictures/' . $PersonDetailResult['profile_picture'] ); // Use `public_path` to get the full path

            //Log::info("the old image is:". $oldImagePath );
            if (file_exists($oldImagePath)) {
                //Log::info("it should unlink it");

                unlink($oldImagePath); // Delete the old image
                //Log::info("Deleted old image: " . $oldImagePath);
            }
        }

        $PersonDetailResult->editor_id= $this->userId;
        $PersonDetailResult->save(); 

        $PersonDetailResult_filled= $PersonDetailResult->delete();  
        return $PersonDetailResult;

        
    }
}