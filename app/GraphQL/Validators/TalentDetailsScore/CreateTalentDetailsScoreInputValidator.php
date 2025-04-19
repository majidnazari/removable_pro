<?php

namespace App\GraphQL\Validators\TalentDetailsScore;

use App\GraphQL\Enums\Status;
use Nuwave\Lighthouse\Validation\Validator as GraphQLValidator;
use Illuminate\Support\Facades\Log;
use App\Traits\AuthUserTrait;
use App\Traits\FindOwnerTrait;
use App\Rules\TalentDetailsScore\TalentDetailsScoreAuthority;
use App\Rules\TalentDetailsScore\ParticipatingUserNotLoggedIn;
use App\Rules\TalentHeader\CheckStatus;
use App\Rules\TalentHeader\CheckEndDate;
use App\Rules\TalentDetailsScore\CheckEndDateFromTalentDetailScore;
use Exception;

class CreateTalentDetailsScoreInputValidator extends GraphQLValidator
{
    use AuthUserTrait;
    use FindOwnerTrait;

    /**
     * Define the validation rules for the Create and Update Favorite inputs.
     */
    public function rules(): array
    {
        return [
            // 'participating_user_id'=> ['required', 'exists:users,id', new ParticipatingUserNotLoggedIn],
            'talent_detail_id' => [
                'required', 
                'exists:talent_details,id', 
                new TalentDetailsScoreAuthority,
                new CheckEndDateFromTalentDetailScore($this->arg('talent_detail_id')),
            ],
            
        ];
    }
    
}
