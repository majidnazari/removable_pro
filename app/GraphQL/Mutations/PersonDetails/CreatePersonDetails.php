<?php

namespace App\GraphQL\Mutations\PersonDetails;

use App\GraphQL\Enums\PhysicalCondition;
use App\Models\PersonDetail;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use Illuminate\Support\Facades\Storage;
use App\GraphQL\Enums\Status;
use App\Traits\AuthUserTrait;
use App\Traits\DuplicateCheckTrait;

use Log;

final class CreatePersonDetails
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
    public function resolvePersonDetail($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {
        $this->userId = $this->getUserId();

        // $isExist = PersonDetail::where('person_id', $args['person_id'])->first();
        
        // if ($isExist) {
        //     return Error::createLocatedError("PersonDetail-CREATE-RECORD_IS_EXIST");
        // }        
       
        $this->checkDuplicate(new PersonDetail(),  $args['person_id']);
        //Log::info("the args are: " . $args['profile_picture']);
        $path="";
        // Check if the file exists in the input
                if (isset($args['profile_picture'])) 
                {
                    $file = $args['profile_picture'];
                   
                    // Check if the file is valid
                    if (!$file->isValid()) {
                        throw new Error('Invalid file upload.');
                    }

                     // Define allowed file types and max size (in bytes)
                    $allowedFileTypes = ['jpg', 'jpeg', 'png', 'gif'];
                    $maxFileSize = 1 * 1024 * 1024; // 1 MB

                    // Get the file extension and size
                    $extension = $file->getClientOriginalExtension();
                    $fileSize = $file->getSize();

                    // Check file type
                    if (!in_array(strtolower($extension), $allowedFileTypes)) {
                        throw new Error('Invalid file type. Allowed types: ' . implode(', ', $allowedFileTypes));
                    }

                    // Check file size
                    if ($fileSize > $maxFileSize) {
                        throw new Error('File size exceeds the maximum limit of 1 MB.');
                    }

                    // Store the file with a unique name
                    // $path = 'profile_pictures/' . time() . '_' . $file->getClientOriginalName();
                    $path = $args['person_id'] . '.' . $file->getClientOriginalExtension();
                    Storage::disk('public')->put("profile_pictures/".$path, file_get_contents($file->getRealPath()));
                  
                    // Get the URL for the stored file
                    $profilePictureUrl = Storage::url($path);
                    $args['profile_picture'] = $profilePictureUrl;
                }

         // Prepare the model data
        $PersonDetailsModel = [
            "create_id" => 1,
            "person_id" => $args['person_id'],
            "profile_picture" =>  $path ?? null,
            //"gender" => $args['gender'] ?? 'None',
            "physical_condition" => $args['physical_condition'] ?? PhysicalCondition::Healthy // 'Healthy'
        ];
        
        // Check if a similar details profile already exists for the same person_id
      
        // Create the new person detail record
        $PersonDetailResult = PersonDetail::create($PersonDetailsModel);
        return $PersonDetailResult;
       // log::info("the file is:" . json_encode($file));
        //return $file->storePublicly('uploads');

        //Log::info("the args are:" . json_encode($args));
        //
    //     $PersonDetailsModel = [
    //         "create_id" => 1,
    //         "person_id" => $args['person_id'],
    //         "profile_picture" => $args['profile_picture'] ?? null,
    //         "gender" => $args['gender'] ?? 'None', // Default to 'None' if not provided
    //         "physical_condition" => $args['physical_condition'] ?? 'Healthy' // Default to 'Healthy' if not provided
    //     ];
        
    //     // Check if a similar details profile already exists for the same person_id
    //     $is_exist = PersonDetail::where('person_id', $args['person_id'])->first();
        
    //     if ($is_exist) {
    //         return Error::createLocatedError("PersonDetail-CREATE-RECORD_IS_EXIST");
    //     }
    //     $PersonDetailResult = PersonDetail::create($PersonDetailsModel);
    //     return $PersonDetailResult;
     }
}