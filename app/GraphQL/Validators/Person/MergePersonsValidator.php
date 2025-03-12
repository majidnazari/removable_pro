<?php
namespace App\GraphQL\Validators\Person;


use App\Rules\Person\ActivePersonRule;
use App\Rules\Person\MergePersonsRule;
use App\Rules\Person\CheckClanMatchRule;
use App\Rules\Person\OwnerMergeRule;
use Nuwave\Lighthouse\Validation\Validator;

class MergePersonsValidator extends Validator
{
    public function rules(): array
    {
        $primaryPersonId = $this->arg('primaryPersonId');
        $secondaryPersonId = $this->arg('secondaryPersonId');

        return [
            'primaryPersonId' => [
                'required',
                'integer',
                new ActivePersonRule(),
            ],
            'secondaryPersonId' => [
                'required',
                'integer',
                new ActivePersonRule(),
                new MergePersonsRule($primaryPersonId, $secondaryPersonId),
                new CheckClanMatchRule($primaryPersonId, $secondaryPersonId),
                new OwnerMergeRule($primaryPersonId, $secondaryPersonId),
                
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'primaryPersonId.required' => 'The primary person ID is required.',
            'secondaryPersonId.required' => 'The secondary person ID is required.',
        ];
    }
}
