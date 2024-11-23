<?php

namespace App\GraphQL\Mutations\Memory;

use App\Models\Memory;
use GraphQL\Type\Definition\ResolveInfo;
use App\Models\GroupUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Joselfonseca\LighthouseGraphQLPassport\Memorys\PasswordUpdated;
use Joselfonseca\LighthouseGraphQLPassport\Exceptions\ValidationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use Illuminate\Support\Facades\Storage;
use App\GraphQL\Enums\Status;
use Illuminate\Support\Facades\Auth;
use Exception;


use Log;

final class CreateMemory
{
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
    public function resolveMemory($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {

        $user = Auth::guard('api')->user();

        if (!$user) {
            throw new Exception("Authentication required. No user is currently logged in.");
        }

        $this->userId = $user->id;;

        $MemoryModel = [
            "creator_id" => $this->userId,
            "person_id" => $args['person_id'],
            "category_content_id" => $args['category_content_id'],
            "group_view_id" => $args['group_view_id'],            
            "title" => $args['title'],
            // "content" => $args['content'],
            // "description" => $args['description'],
            // "is_shown_after_death" => $args['is_shown_after_death'],
            // "status" => $args['status']
        ];
      

        //Log::info("the args are:" . $args['content']);
       
        $is_exist = Memory::where($MemoryModel)->first();
        if ($is_exist) {
            return Error::createLocatedError("Memory-CREATE-RECORD_IS_EXIST");
        }
      
        $path="";
       
        if (isset($args['content'])) 
        {
            $file = $args['content'];
            if (!$file->isValid()) {
                throw new Error('Invalid file upload.');
            }
            // Dynamically handle file types based on category_content_id
            $categoryContentId = $args['category_content_id'];
            // switch ($categoryContentId) {
            //     case 1: // Voice
            //         $allowedFileTypes = ['mp3', 'wav'];
            //         $maxFileSize = 10 * 1024 * 1024; // 10 MB
            //         break;

            //     case 2: // Video
            //         $allowedFileTypes = ['mp4', 'avi', 'mkv','mov'];
            //         $maxFileSize = 100 * 1024 * 1024; // 100 MB
            //         break;

            //     case 3: // Image
            //         $allowedFileTypes = ['jpg', 'jpeg', 'png', 'gif'];
            //         $maxFileSize = 20 * 1024 * 1024; // 20 MB
            //         break;

            //     case 4: // Text
            //         $allowedFileTypes = ['txt', 'pdf', 'docx'];
            //         $maxFileSize = 1 * 1024 * 1024; // 5 MB
            //         break;

            //     default:
            //         throw new Error('Invalid category_content_id.');
            // }

            $allowedFileTypes = ['jpg', 'jpeg', 'png', 'gif','mp3','mp4'];
            $maxFileSize = 10 * 1024 * 1024; // 1 MB

            $extension = $file->getClientOriginalExtension();
            $fileSize = $file->getSize();

           // Log::info("the file nme is: " . $args['person_id'] . '.' . $file->getClientOriginalExtension());

            if (!in_array(strtolower($extension), $allowedFileTypes)) {
                throw new Error('Invalid file type. Allowed types: ' . implode(', ', $allowedFileTypes));
            }

            if ($fileSize > $maxFileSize) {
                throw new Error('File size exceeds the maximum limit of 1 MB.');
            }

            $path = $args['person_id'] . '.' . $file->getClientOriginalExtension();
            Storage::disk('public')->put("memories/".$path, file_get_contents($file->getRealPath()));
            
            $profilePictureUrl = Storage::url($path);
            $args['content'] = $profilePictureUrl;
        }

        $MemoryModel['content']=$path ?? "";
        $MemoryModel['description']= $args['description'] ?? "";
        $MemoryModel['is_shown_after_death']= $args['is_shown_after_death'] ?? false;
        $MemoryModel['status']= $args['status'] ?? Status::None;
        
        $MemoryModelResult = Memory::create($MemoryModel);
        return $MemoryModelResult;

    }
}