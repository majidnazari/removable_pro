<?php

namespace App\GraphQL\Validators\Share;

use Nuwave\Lighthouse\Validation\Validator as GraphQLValidator;
use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Log;
use App\Traits\AuthUserTrait;
use Log;

class UserOwnsRecordValidator extends GraphQLValidator
{
    use AuthUserTrait;

    /**
     * Explicit table name mapping for fields.
     */
    protected array $tableMap = [
        'person_id' => 'people',
        'group_id' => 'groups',
        'event_id' => 'events',
        // 'category_content_id' => 'category_contents',
        'group_category_id' => 'group_categories',
        // Add more mappings as needed
    ];

    /**
     * Fields to exclude from creator_id validation.
     */
    protected array $excludedFields = [
        'category_content_id', // Example: no creator_id validation for this field
        'user_id'
    ];
    protected array $justCheckCurrentUserLoggedIn = [
        'event_id',
        'group_category_id' => 'group_categories',

    ];

    public function rules(): array
    {
        $rules = [];
        $user = $this->getUser(); // Logged-in user's ID

        if ($user->isAdmin() || $user->isSupporter()) {
            // Admins and Supporters can perform any action
            return $rules;
        }
        /** @var \Nuwave\Lighthouse\Execution\Arguments\ArgumentSet $arguments */
        $arguments = $this->args;

        // Extract fields ending with '_id' and their values
        $fields = collect($arguments->arguments)
            ->filter(function ($argument, $key) {
                return str_ends_with($key, '_id');
            })
            ->mapWithKeys(function ($argument, $key) {
                return [$key => $argument->value];
            });

        //Log::info("Fields detected for validation: " . json_encode($fields));

        // Get all allowed `creator_id` values: the logged-in user and users connected via `user_merge_requests`
        $allowedCreatorIds = $this->getAllowedCreatorIds($user->id);


        // Apply validation rules dynamically for each '_id' field
        foreach ($fields as $fieldName => $value) {
            $tableName = $this->resolveTableName($fieldName);

            // Base validation: required and exists
            $rules[$fieldName] = ['required', 'exists:' . $tableName . ',id'];

            // // Skip creator_id check for excluded fields
            // if (!in_array($fieldName, $this->excludedFields)) {
            //     $rules[$fieldName][] = function ($attribute, $value, $fail) use ($tableName, $user, $allowedCreatorIds) {
            //         $exists = DB::table($tableName)
            //             ->where('id', $value)
            //             ->whereIn('creator_id', $allowedCreatorIds)
            //             ->exists();

            //         //Log::info("Validation check for {$attribute} in {$tableName}: " . json_encode($exists));

            //         if (!$exists) {
            //             $fail("The selected {$attribute} does not belong to the authenticated user.");
            //         }
            //     };
            // }

            // Check if the field is in the $newItems list (i.e., should only check creator_id against logged-in user)
            if (in_array($fieldName, $this->justCheckCurrentUserLoggedIn)) {
                // Only check creator_id with the logged-in user
                $rules[$fieldName][] = function ($attribute, $value, $fail) use ($tableName, $user) {
                    $exists = DB::table($tableName)
                        ->where('id', $value)
                        ->where('creator_id', $user->id) // Check only with logged-in user's creator_id
                        ->whereNull('deleted_at') // Exclude soft-deleted records
                        ->exists();

                    if (!$exists) {
                        $fail("The selected {$attribute} does not belong to the authenticated user.");
                    }
                };
            } else {
                // Skip creator_id check for excluded fields
                if (!in_array($fieldName, $this->excludedFields)) {
                    // Check creator_id against allowedCreatorIds (including merged users)
                    $rules[$fieldName][] = function ($attribute, $value, $fail) use ($tableName, $user, $allowedCreatorIds) {
                        $exists = DB::table($tableName)
                            ->where('id', $value)
                            ->whereIn('creator_id', $allowedCreatorIds)
                            ->whereNull('deleted_at') // Exclude soft-deleted records
                            ->exists();

                        if (!$exists) {
                            $fail("The selected {$attribute} does not belong to the authenticated user.");
                        }
                    };
                }
            }
        }

        return $rules;
    }

    /**
     * Resolve table name from field name using the table map.
     */
    protected function resolveTableName(string $fieldName): string
    {
        return $this->tableMap[$fieldName] ?? str_replace('_id', '', $fieldName) . 's';
    }

    protected function getAllowedCreatorIds(int $userId): array
    {
        // Start with the logged-in user ID
        $allowedCreatorIds = [$userId];

        // Get all user_receiver_id values where the logged-in user is the sender and status is Complete (4)
        $connectedUserIds = DB::table('user_merge_requests')
            ->where('user_sender_id', $userId)
            ->where('status', 4) // Status = Complete
            ->whereNull('deleted_at') // Exclude soft-deleted records
            ->pluck('user_receiver_id')
            ->toArray();

        // Merge the connected user IDs into the allowed IDs
        $allowedCreatorIds = array_merge($allowedCreatorIds, $connectedUserIds);
       // Log::info("the all alowed user are:" . json_encode($allowedCreatorIds));
        return $allowedCreatorIds;
    }
}
