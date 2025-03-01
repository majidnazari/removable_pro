<?php

namespace App\GraphQL\Mutations\FamilyReport;

use App\GraphQL\Enums\MarriageStatus;
use App\Models\FamilyReport;
use App\Models\TalentDetailScore;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\GraphQL\Enums\Status;
use App\Traits\AuthUserTrait;
use App\Traits\DuplicateCheckTrait;
use App\Traits\GetAllowedAllUsersInClan;
use App\Traits\FindOwnerTrait;
use App\Models\Person;
use App\Models\PersonMarriage;
use App\Models\PersonChild;
use App\Models\User;
use App\Models\TalentHeader;
use App\Models\MiddleField;
use App\Models\MajorField;
use App\Models\MinorField;
use Carbon\Carbon;

use Log;

final class FamilyHeaderReports
{
    use AuthUserTrait;
    use DuplicateCheckTrait;
    use GetAllowedAllUsersInClan;
    use FindOwnerTrait;

    protected $userId;
    protected $users;

    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    // public function resolveFamilyHeaderReport($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    // {
    //     $this->userId = $this->getUserId();
    //     $person = $this->findOwner();
    //     $major = [];

    //     $talentHeaders = TalentHeader::with(['TalentDetails', 'TalentDetails.MinorField.MiddleField.MajorField'])
    //         ->where('person_id', $person->id)
    //         ->get();

    //     //Log::info("the  talentHeaders are:" . json_encode($talentHeaders));
    //     $allMajorIds=MajorField::where();

    //     foreach ($talentHeaders as $talentHeader) {

    //         $allMiddleIds=MiddleField::where('major_field_id', $talentHeader->id)->pluck('id');

    //         // Log::info("the TalentDetails is :" . json_encode($talentHeader->TalentDetails));
    //         foreach ($talentHeader->TalentDetails as $talentDetail) {
    //             // Log::info("the MinorField is :" . json_encode($talentDetail->MinorField));
    //             // Log::info("the middle_field is :" . json_encode($talentDetail->MinorField->MiddleField->MajorField));
    //             $score=TalentDetailScore::where('talent_detail_id',$talentDetail)

    //             $major[] = [
    //                 "id" => $talentDetail->MinorField->MiddleField->MajorField->id,
    //                 "title" => $talentDetail->MinorField->MiddleField->MajorField->title,
    //             ];


    //         }

    //     }
    //     // Make the list unique based on 'id'
    //     $major = collect($major)->unique('id')->values()->all();

    //     Log::info("the major is :" . json_encode($major));

    //     // Log::info("the majorList are:" . json_encode($major));


    //     //Log::info("the talentHeaders are:" . json_encode($talentHeaders));

    //     // $majorList = $talentHeaders->Map(function ($talentHeader) {
    //     //  //return $talentHeader;
    //     //     return $talentHeader->TalentDetails->Map(function ($talentDetail) {
    //     //         //return $talentDetail;
    //     //         return $talentDetail->MinorField->MiddleField->Map(function ($middleField) {
    //     //             return $middleField->MajorField->Map(function ($majorField) {
    //     //                 return [
    //     //                     'major_id' => $majorField->id,
    //     //                     'major_title' => $majorField->title,
    //     //                 ];
    //     //             });
    //     //         });
    //     //     });
    //     // })->flatten(2);

    //     // $majorList = $majorList->unique(function ($item) {
    //     //     return $item['major_id'];
    //     // });
    //     //dd($majorList);


    //     return $major;
    // }


}