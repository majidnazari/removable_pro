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
use App\Exceptions\CustomValidationException;

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
    public function resolvePersonDetail($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $this->userId = $this->getUserId();

        $this->checkDuplicate(new PersonDetail(), $args['person_id']);
        $path = "";
        // Check if the file exists in the input
        if (isset($args['profile_picture'])) {
            $file = $args['profile_picture'];

            // Check if the file is valid
            if (!$file->isValid()) {
                throw new CustomValidationException('Invalid file upload.', "آپلود فایل نامعتبر است.", 400);

                //throw new Error('Invalid file upload.');
            }

            // Define allowed file types and max size (in bytes)
            $allowedFileTypes = ['jpg', 'jpeg', 'png', 'gif'];
            $maxFileSize = 1 * 1024 * 1024; // 1 MB

            // Get the file extension and size
            $extension = $file->getClientOriginalExtension();
            $fileSize = $file->getSize();

            // Check file type
            if (!in_array(strtolower($extension), $allowedFileTypes)) {
                throw new CustomValidationException('Invalid file type. Allowed types: ', 'نوع فایل نامعتبر است. انواع مجاز: ', 400);

                //throw new Error('Invalid file type. Allowed types: ' . implode(', ', $allowedFileTypes));
            }

            // Check file size
            if ($fileSize > $maxFileSize) {
                throw new CustomValidationException("File size exceeds the maximum limit of 1 MB.", "اندازه فایل از حداکثر 1 مگابایت بیشتر است.", 400);

                //throw new Error('File size exceeds the maximum limit of 1 MB.');
            }

            $path = $args['person_id'] . '.' . $file->getClientOriginalExtension();
            Storage::disk('public')->put("profile_pictures/" . $path, file_get_contents($file->getRealPath()));

            // Get the URL for the stored file
            $profilePictureUrl = Storage::url($path);
            $args['profile_picture'] = $profilePictureUrl;
        }

        // Prepare the model data
        $PersonDetailsModel = [
            "create_id" => 1,
            "person_id" => $args['person_id'],
            "profile_picture" => $path ?? null,
            //"gender" => $args['gender'] ?? 'None',
            "physical_condition" => $args['physical_condition'] ?? PhysicalCondition::Healthy // 'Healthy'
        ];

        // Check if a similar details profile already exists for the same person_id

        // Create the new person detail record
        $PersonDetailResult = PersonDetail::create($PersonDetailsModel);
        return $PersonDetailResult;

    }
}