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
       // Log::info("the args rae:" . json_encode($args));
        $this->userId = $this->getUserId();

        try {

            $TalentDetailScoreResult = $this->userAccessibility(TalentDetailScore::class, AuthAction::Update, $args);

        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        $talentDetailsScore=TalentDetailScore::where('id',$args['id'])->with(['TalentDetail','TalentDetail.TalentHeader'])->first();

        if(isset($talentDetailsScore->Creator_id) && ($talentDetailsScore->Creator_id != $this->userId ))
        {
            throw new Exception("You Can Just Your Own Score.");
        }
        if(isset($talentDetailsScore->TalentDetail->TalentHeader->end_date) && ($talentDetailsScore->TalentDetail->TalentHeader->end_date < Carbon::now()->format("Y-m-d")))
        {
            throw new Exception("This Talent Time Finished To Score!");
        }
        
       //Log::info("the talentDetailsScore rae:" . json_encode($talentDetailsScore->TalentDetail->TalentHeader->end_date));

        // $this->checkDuplicate(
        //     new TalentDetailScore(),
        //     $args,
        //     ['id', 'editor_id', 'created_at', 'updated_at','score','status'],
        //     excludeId: $args['id']
        // );

        return $this->updateModel($TalentDetailScoreResult, $args, $this->userId);
       
    }
}