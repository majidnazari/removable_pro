<?php

namespace App\GraphQL\Mutations\User;

use App\Models\IpTracking;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Joselfonseca\LighthouseGraphQLPassport\GraphQL\Mutations\BaseAuthResolver;
use Carbon\Carbon;
use App\Models\User;

use GraphQL\Error\Error;

use Log;

class RegisterMobile
{
    public $today ;

    public function __construct(){
         $this->today = Carbon::now()->format("Y-m-d");

    }

    public $error = [
        "Code" => "",
        "Message" => ""
    ];
             


    public function resolve($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {

        // Log::info("the inside of resolve is running");
        $code = rand(0, 99999999);
        $expired_at = Carbon::now()->addMinutes(2)->format("Y-m-d H:i:s");
        $args['sent_code'] = $code;
        $args['code_expired_at'] = $expired_at;
        $ip_tracking_model = [
            "ip" => request()->ip(),
           // "date_attemp" => Carbon::now()->format("Y-m-d H:i:s"),
           // "today_attemp" => 1,
          //  "total_attemp" => 1,

        ];
        // $args['ip'] = 
        // $args['ip_attemp_time'] = 1;
        // $args['ip_attemp_times'] = $today;
        $args['user_attemp_time'] = 1;
        $args['user_attemp_times'] = $this->today;
        $user = User::create($args);
        $ip_tracking = IpTracking::firstOrCreate($ip_tracking_model);

        //Log::info("the user is:" . json_encode($user));

        //$this->validateAuthModel($model);

        // if ($model instanceof MustVerifyEmail) {
        //     $model->sendEmailVerificationNotification();

        //     event(new Registered($model));

        //     return [
        //         'tokens' => [],
        //         'status' => 'MUST_VERIFY_EMAIL',
        //     ];
        // }
        // $credentials = $this->buildCredentials([
        //     'username' => $args[config('lighthouse-graphql-passport.username')],
        //     'password' => $args['password'],
        // ]);
        //$user = $model->where(config('lighthouse-graphql-passport.username'), $args[config('lighthouse-graphql-passport.username')])->first();
        // Log::info("the model of fetched is" . json_encode($user));
        // Log::info("the makeRequest and credentials is" . json_encode($credentials));

        // $response = $this->makeRequest($credentials);
        //Log::info("the response  is:" . json_encode($response));

        //$response['user'] = $user;
        //event(new Registered($user));

        return [
            //'tokens' => $response,
            'user_id' => $user->id,
            'status' => 'SUCCESS',
        ];
    }

    public function VerifyMobileresolve($rootValue, array $args, GraphQLContext $context = null, ResolveInfo $resolveInfo)
    {
        // self::check_ip_Activity(request()->ip());
        // if ($this->error["Code"] != "") {
        //     return $this->error;
        // }
        // self::check_user_Activity($args['user_id'],$args['code']);

        // if ($this->error["Code"] != "") {
        //     return $this->error;
        // }

        $user=User::where('id',$args['user_id'])
        ->where('sent_code',$args['code'])
        ->where('code_expired_at','<=',Carbon::now()->format('Y-m-d H:i:s'))
        ->first();

        if(!$user){
            return Error::createLocatedError("THE CODE IS INVALID!");


        }
        $user->mobile_is_veryfied=1;
        $user->status="Active";   
        $user->save();

        return [
            "Code" => 200,
            "Message" => "USER MOBILE IS VERIFIED "
        ];
        // return [
        //     //'tokens' => $response,
        //     'user_id' => $args['user_id'],
        //     'status' => 'SUCCESS',
        // ];
    }

    function check_ip_Activity($ip)
    {

        $ip_tracking = IpTracking::where('ip', $ip)->first();


        if (!$ip_tracking) {
            $ip_tracking_model =
            [
                "ip" => $ip,
                "date_attemp" => date("Y-m-d H:i:s"),
                "today_attemp" => 1,
                "total_attemp" => 1,
            ];
            IpTracking::create($ip_tracking_model);
        }

        if (($ip_tracking->status == "Blocked") && ($ip_tracking->expire_blocked_time == null)) {
            $this->error =
            [
                "Code" => "ip",
                "Message" => "THE IP IS BLOCKED PLEASE CALL TO SUPPORTER!"
            ];
            //return;
            //return Error::createLocatedError("THE IP IS BLOCKED PLEASE CALL TO SUPPORTER!");

        }
        if (($ip_tracking->status == "Blocked") && ($ip_tracking->expire_blocked_time > Carbon::now()->format("Y-m-d H:i:s"))) {
            $remain_time = Carbon::now()->diffInMinutes(Carbon::parse($ip_tracking->expire_blocked_time)->format("Y-m-d H:i:s"), false);

            $this->error =
            [
                "Code" => "ip",
                "Message" => "THE IP IS BLOCKED TILL " . $remain_time . " MINUTE(S)"
            ];

            //return Error::createLocatedError("THE IP IS BLOCKED TILL ". $remain_time . " MINUTE(S)");

        } else if (($ip_tracking->status == "Blocked") && ($ip_tracking->expire_blocked_time < Carbon::now()->format("Y-m-d H:i:s"))) {

            $ip_tracking->status = "Active";
            $ip_tracking->today_attemp = 0;
            $ip_tracking->date_attemp = $this->today;
            $ip_tracking->save();

        }


        // if ($this->error['Code']==""  && ($ip_tracking->status == "Active")) {
        //     $ip_tracking->today_attemp++;
        //     $ip_tracking->number_of_blocked_times++;            
        //     $ip_tracking->save();


        // }
        if (($ip_tracking->today_attemp >= 10) && ($ip_tracking->status == "Active")) {
            $ip_tracking->status = "Blocked";
            $ip_tracking->today_attemp++;
            $ip_tracking->total_attemp++;
            //$ip_tracking->today_attemp = 0;
            $ip_tracking->expire_blocked_time = Carbon::now()->addHours(1)->format("Y-m-d H:i:s");
            $ip_tracking->number_of_blocked_times++;
            $ip_tracking->save();
        } else if( // for simple way if the conditions are normal it shouldb be addedd ip address as a counter 
            ($ip_tracking->status == "Active") && ($ip_tracking->expire_blocked_time < Carbon::now()->format("Y-m-d H:i:s")) || 
            ($ip_tracking->status == "Active") && ($ip_tracking->expire_blocked_time =="") 

        ) {
            $ip_tracking->today_attemp++;
            $ip_tracking->total_attemp++;
            //$ip_tracking->number_of_blocked_times++;            
            $ip_tracking->save();
        }


    }
    function check_user_Activity($id,$code)
    {
       // $today = Carbon::now()->format("Y-m-d");

        $user = User::where('id', $id)->where('status','None')->where('sent_code','code')->first();

        Log::info("the user is:" . json_encode($user));
        if (!$user) {
            $this->error =
                [
                    "Code" => "user",
                    "Message" => "USER:NOT FOUND!"
                ];
            //return;
            //return Error::createLocatedError("USER:NOT FOUND!");

        }
        
        if ($user && ($user->status == "Blocked") && ($user->expire_blocked_time == null)) {
            $this->error =
                [
                    "Code" => "user",
                    "Message" => "THE USER IS BLOCKED PLEASE CALL TO SUPPORTER!"
                ];
            //return;
            // return Error::createLocatedError("THE USER IS BLOCKED PLEASE CALL TO SUPPORTER!");

        }

        if ($user && ($user->status == "Blocked") && ($user->expire_blocked_time > Carbon::now()->format("Y-m-d H:i:s"))) {
            $remain_time = Carbon::now()->diffInMinutes(Carbon::parse($user->expire_blocked_time)->format("Y-m-d H:i:s"), false);

            $this->error =
                [
                    "Code" => "user",
                    "Message" => "THE USER IS BLOCKED TILL " . $remain_time . " MINUTE(S)"
                ];
            //return;
            //return Error::createLocatedError("THE USER IS BLOCKED TILL ". $remain_time . " MINUTE(S)");

        }
        else if ($user && ($user->status == "Blocked") && ($user->expire_blocked_time < Carbon::now()->format("Y-m-d H:i:s"))) {

            $user->status = "Active";
            $user->today_attemp=0;
            $user->number_of_blocked_times++;
            $user->date_attemp = $this->today;
            $user->save();
        }       
        if ( $user && ($user->today_attemp >= 5 && $user->date_attemp == $this->today) && ($user->status == "Active")) {
            //$user['user_attemp_date'] = $user['user_attemp_date'] == $today ? $user['user_attemp_date'] : $today;
            $user->status = "Blocked";
             $user->number_of_blocked_times++;
            $user->today_attemp++;
            $user->total_attemp++;
            $user->expire_blocked_time = Carbon::now()->addHours((1))->format('Y-m-d H:i:s');
            $user->save();
            // return Error::createLocatedError("THE USER iS BLOCKED FOR 1 HOUR!");

        }
        else if( // for simple way if the conditions are normal it shouldb be addedd ip address as a counter 
            $user && ($user->status != "Active") && ($user->expire_blocked_time < Carbon::now()->format("Y-m-d H:i:s")) || 
            $user && ($user->status != "Active") && ($user->expire_blocked_time =="") 

        ) {
            $user->today_attemp++;
            $user->total_attemp++;
            //$ip_tracking->number_of_blocked_times++;            
            $user->save();
        }

    }
}
