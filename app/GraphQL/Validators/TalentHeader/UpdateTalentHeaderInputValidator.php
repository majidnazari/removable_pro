<?php

namespace App\GraphQL\Validators\TalentHeader;

use App\GraphQL\Enums\Status;
use Nuwave\Lighthouse\Validation\Validator as GraphQLValidator;
use Illuminate\Support\Facades\Log;
use App\Traits\AuthUserTrait;
use App\Traits\FindOwnerTrait;
use App\Rules\TalentHeader\CheckPersonOfEachUser;
use App\Rules\TalentHeader\GroupCategoryOwnership;
use App\Rules\TalentHeader\CheckStatus;
use App\Rules\TalentHeader\CheckEndDate;
use Exception;

class UpdateTalentHeaderInputValidator extends GraphQLValidator
{
    use AuthUserTrait;
    use FindOwnerTrait;

    /**
     * Define the validation rules for the Create and Update Favorite inputs.
     */
    public function rules(): array
    {
       
        return [
            'group_category_id' => ['nullable', 'exists:group_categories,id', new GroupCategoryOwnership],
            'person_id' => ['nullable', 'exists:people,id', new CheckPersonOfEachUser()],
            'end_date' => ['nullable', 'date', new CheckEndDate],
            'status' => ['nullable'],
            'title' => ['nullable', 'string', 'max:255'],
        ];
    }
    
}
