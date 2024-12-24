<?php

namespace App\GraphQL\Validators\Share;

use Nuwave\Lighthouse\Validation\Validator as GraphQLValidator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\AuthUserTrait;

class UserOwnsRecordValidator extends GraphQLValidator
{
    use AuthUserTrait;

    /**
     * Explicit table name mapping for fields.
     */
    protected array $tableMap = [
        'person_id' => 'people',
        'group_id' => 'groups',

       // 'category_content_id' => 'category_contents',
        'group_category_id' => 'group_categories',
        // Add more mappings as needed
    ];

    /**
     * Fields to exclude from creator_id validation.
     */
    protected array $excludedFields = [
        'category_content_id', // Example: no creator_id validation for this field
        'event_id', // Example: no creator_id validation for this field
        'user_id'
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

        Log::info("Fields detected for validation: " . json_encode($fields));

      

        // Apply validation rules dynamically for each '_id' field
        foreach ($fields as $fieldName => $value) {
            $tableName = $this->resolveTableName($fieldName);

            // Base validation: required and exists
            $rules[$fieldName] = ['required', 'exists:' . $tableName . ',id'];

            // Skip creator_id check for excluded fields
            if (!in_array($fieldName, $this->excludedFields)) {
                $rules[$fieldName][] = function ($attribute, $value, $fail) use ($tableName, $user) {
                    $exists = DB::table($tableName)
                        ->where('id', $value)
                        ->where('creator_id', $user->id)
                        ->exists();

                    Log::info("Validation check for {$attribute} in {$tableName}: " . json_encode($exists));

                    if (!$exists) {
                        $fail("The selected {$attribute} does not belong to the authenticated user.");
                    }
                };
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
}
