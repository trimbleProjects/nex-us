<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use App\Models\tracker;
use App\Models\User;
use Exception;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function SaveRequest($request, $type, $rate, $amount, $taxAmount, $county, $ip, $user){
        try{
            // $user = User::where('bearer_token', '=', $request['apikey'])->first();

            tracker::insert(array('user_id' => $user->id,
                                'ip' => $ip,
                                'type' => $type,
                                'rate' => $rate,
                                'amount' => $amount,
                                'tax_amount' => $taxAmount, //Round($amount *  ($rate/100),2),
                                'city' => $request['city'],
                                'state' => $request['state'],
                                'zip_code' => $request['zipCode'],
                                'county' => $county
                            ));
            return true;
        }
        catch(Exception $e){

            tracker::insert(array('user_id' => -1,
                                'ip' => $ip,
                                'type' => $type,
                                'rate' => $rate,
                                'amount' => $amount,
                                'tax_amount' => $taxAmount, Round($amount *  ($rate/100),2),
                                'city' => $request['city'],
                                'state' => $request['state'],
                                'zip_code' => $request['zipCode'],
                                'county' => $county
                            ));

            return false;
        }
    }
}
