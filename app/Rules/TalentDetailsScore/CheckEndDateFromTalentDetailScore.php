<?php

namespace App\Rules\TalentDetailsScore;

use Illuminate\Contracts\Validation\Rule;
use Carbon\Carbon;
use App\Models\TalentHeader;
use App\Models\TalentDetail;

class CheckEndDateFromTalentDetailScore implements Rule
{
    /**
     * The talent_detail_id.
     *
     * @var mixed
     */
    protected $talentDetailId;

    /**
     * Create a new rule instance.
     *
     * @param  mixed  $talentDetailId
     * @return void
     */
    public function __construct($talentDetailId)
    {
        $this->talentDetailId = $talentDetailId;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Get the TalentHeader associated with the given talent_detail_id
        $talentDetail = TalentDetail::find($this->talentDetailId);

        if ($talentDetail) {
            // Check if there is an associated TalentHeader
            $talentHeader = $talentDetail->talentHeader; // Assuming the relationship is set properly

            if ($talentHeader && $talentHeader->end_date) {
                // Compare the current time with the end_date
                return Carbon::now()->lte(Carbon::parse($talentHeader->end_date));
            }
        }

        return true; // Pass validation if there's no end_date or no TalentHeader
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The current time has passed the TalentHeader end date, so you cannot create a TalentDetailScore.';
    }
}
