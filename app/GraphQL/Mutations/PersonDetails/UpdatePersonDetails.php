<?php

namespace App\GraphQL\Mutations\PersonDetails;

use App\GraphQL\Enums\PhysicalCondition;
use App\Models\PersonDetail;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Illuminate\Support\Facades\Storage;
use GraphQL\Error\Error;
use App\GraphQL\Enums\Status;
use Illuminate\Support\Facades\Auth;
use Exception;

use Log;


final class UpdatePersonDetails
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
    public function resolvePersonDetail($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {
        $user = Auth::guard('api')->user();

        if (!$user) {
            throw new Exception("Authentication required. No user is currently logged in.");
        }

        $this->userId = $user->id;;
        //args["user_id_creator"]=$this->userId;
        $personDetail = PersonDetail::find($args['id']);
       

        $fileName="";

        if (!$personDetail) {
            return Error::createLocatedError("PersonDetails-UPDATE-RECORD_NOT_FOUND");
        }


        // Fetch existing record if it exists
        //$personDetail = PersonDetail::find($args['id']); // Assuming you pass `id` to identify the record

        // Validate and handle the file upload
        if (isset($args['profile_picture'])) {
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

            // Get the person ID
            $personId = $args['person_id'];

            // Generate the file name using person_id and the original file extension
            $fileName = "{$personId}.{$extension}"; // or use a unique identifier as mentioned before
            $storagePath = 'profile_pictures/' . $fileName;

            //Log::info("the old pic is:" . $personDetail['profile_picture'] );
            // Delete the old image if it exists
            if ($personDetail && $personDetail['profile_picture'] !=null ) {
                $oldImagePath = public_path('storage/profile_pictures/' . $personDetail['profile_picture'] ); // Use `public_path` to get the full path
                //Log::info("the old image in updateis: ". $oldImagePath );

                if (file_exists($oldImagePath)) {
                   // Log::info("it should unlink it");
                    unlink($oldImagePath); // Delete the old image
                    //Log::info("Deleted old image: " . $oldImagePath);
                }
            }


            // $path = $args['person_id'] . '.' . $file->getClientOriginalExtension();
            // Storage::disk('public')->put("profile_pictures/".$path, file_get_contents($file->getRealPath()));

            //Log::info("storagePath path: " . $storagePath);

            // Store the new file
            Storage::disk('public')->put('profile_pictures/' . $fileName, file_get_contents($file->getRealPath()));

            // Get the URL for the stored file
            $profilePictureUrl = Storage::url($storagePath);

            // Log the stored path for debugging
            //Log::info("Stored new file path: " . $profilePictureUrl);

            // Update the input args or the model directly
            //$args['profile_picture'] = $fileName; // Adjust if using a different variable structure
        }

        //Log::info("the file name is: " . $fileName);


           // Prepare the model data
           $PersonDetailsModel = [
            "editor_id" => $this->userId,
            // "person_id" => $args['person_id'],
            // "profile_picture" =>  $path ?? null,
            //"gender" => $args['gender'] ?? 'None',
            "physical_condition" => $args['physical_condition'] ??  PhysicalCondition::Healthy // 'Healthy'
        ];



        if(!empty($fileName))
        {
            $PersonDetailsModel['profile_picture']=$fileName;
        }
        //Log::info("the PersonDetailsModel is: " . json_encode($PersonDetailsModel));

        // Update the person detail record
        if ($personDetail) {
            $personDetail->fill($PersonDetailsModel); // Assuming `$args` contains all necessary fields to update
            $personDetail->save();       

        } else {
            throw new Error('PersonDetail not found.');
        }

        return $personDetail;

        //    $PersonDetailsResult_filled= $personDetail->fill($PersonDetailsmodel);
        //     $personDetail->save();       

        //     return $personDetail;


    }
}