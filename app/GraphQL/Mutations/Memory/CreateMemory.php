<?php

namespace App\GraphQL\Mutations\Memory;

use App\GraphQL\Enums\ConfirmMemoryStatus;
use App\Models\Memory;
use App\Models\Person;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use Illuminate\Support\Facades\Storage;
use App\GraphQL\Enums\Status;
use App\Traits\AuthUserTrait;
use App\Traits\DuplicateCheckTrait;

use Log;

final class CreateMemory
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
    public function resolveMemory($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {
        $this->userId = $this->getUserId();

        $person=Person::where('id',$args['person_id'])->first();

        $MemoryModel = [
            "creator_id" => $this->userId,
            "person_id" => $args['person_id'],
            "category_content_id" => $args['category_content_id'],
            "group_category_id" => $args['group_category_id'],
            "confirm_status" =>  $person->is_owner==1 ? ConfirmMemoryStatus::Suspend : ConfirmMemoryStatus::Accept,
            "title" => $args['title'],
            // "content" => $args['content'],
            // "description" => $args['description'],
            // "is_shown_after_death" => $args['is_shown_after_death'],
            // "status" => $args['status']
        ];


        //Log::info("the args are:" . $args['content']);

        // $is_exist = Memory::where($MemoryModel)->first();
        // if ($is_exist) {
        //     return Error::createLocatedError("Memory-CREATE-RECORD_IS_EXIST");
        // }

        $path = "";

        if (isset($args['content'])) {
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

            $allowedFileTypes = ['jpg', 'jpeg', 'png', 'gif', 'mp3', 'mp4'];
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
            Storage::disk('public')->put("memories/" . $path, file_get_contents($file->getRealPath()));

            $profilePictureUrl = Storage::url($path);
            $args['content'] = $profilePictureUrl;
        }

        $this->checkDuplicate(
            new Memory(),
            $MemoryModel
        );
        $MemoryModel['content'] = $path ?? "";
        $MemoryModel['description'] = $args['description'] ?? "";
        $MemoryModel['is_shown_after_death'] = $args['is_shown_after_death'] ?? false;
        $MemoryModel['status'] = $args['status'] ?? Status::Active;
        // $MemoryModel['confirm_status'] = $args['confirm_status'] ?? ConfirmMemoryStatus::Accept;
        

        $MemoryModelResult = Memory::create($MemoryModel);
        return $MemoryModelResult;

    }
}