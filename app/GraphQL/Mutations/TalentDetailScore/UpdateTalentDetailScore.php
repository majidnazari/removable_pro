<?php

namespace App\GraphQL\Mutations\TalentDetailScore;

use App\Models\TalentDetailScore;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Error\Error;
use App\Traits\AuthUserTrait;
use App\Traits\AuthorizesMutation;
use App\Traits\DuplicateCheckTrait;
use App\Traits\HandlesModelUpdateAndDelete;
use App\GraphQL\Enums\AuthAction;
use Carbon\Carbon;
use Exception;
use Log;
use App\Exceptions\CustomValidationException;


final class UpdateTalentDetailScore
{
    use AuthUserTrait;
    use AuthorizesMutation;
    use DuplicateCheckTrait;
    use HandlesModelUpdateAndDelete;

    protected $userId;

    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
    }
    public function resolveTalentDetailScore($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $this->userId = $this->getUserId();

        try {

            $TalentDetailScoreResult = $this->userAccessibility(TalentDetailScore::class, AuthAction::Update, $args);

        } catch (CustomValidationException $e) {

            throw new CustomValidationException($e->getMessage(), $e->getMessage(), 500);

        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        $talentDetailsScore = TalentDetailScore::where('id', $args['id'])->with(['TalentDetail', 'TalentDetail.TalentHeader'])->first();

        if (isset($talentDetailsScore->Creator_id) && ($talentDetailsScore->Creator_id != $this->userId)) {
            throw new CustomValidationException("You Can Just Your Own Score.", "شما فقط می توانید امتیاز خود را دریافت کنید.", 403);

        }
        if (isset($talentDetailsScore->TalentDetail->TalentHeader->end_date) && ($talentDetailsScore->TalentDetail->TalentHeader->end_date < Carbon::now()->format("Y-m-d"))) {
            throw new CustomValidationException("This Talent Time Finished To Score!", "این زمان استعداد به امتیاز پایان رسید!", 403);

        }

        return $this->updateModel($TalentDetailScoreResult, $args, $this->userId);

    }
}