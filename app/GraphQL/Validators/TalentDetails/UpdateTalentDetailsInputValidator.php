<?php

namespace App\GraphQL\Validators\TalentDetails;

use App\GraphQL\Enums\Status;
use Nuwave\Lighthouse\Validation\Validator as GraphQLValidator;
use Illuminate\Support\Facades\Log;
use App\Traits\AuthUserTrait;
use App\Traits\FindOwnerTrait;
use App\Rules\TalentDetails\TalentHeaderCreatorCheck;

use Exception;

class UpdateTalentDetailsInputValidator extends GraphQLValidator
{
    use AuthUserTrait;
    use FindOwnerTrait;

    /**
     * Define the validation rules for the Create and Update Favorite inputs.
     */
    public function rules(): array
    {

        return [
            'minor_field_id' => ['nullable', 'exists:minor_fields,id'],
            'talent_header_id' => ['nullable', 'exists:talent_headers,id', new TalentHeaderCreatorCheck],

        ];
    }

}
