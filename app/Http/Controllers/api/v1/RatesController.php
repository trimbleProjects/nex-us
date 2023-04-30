<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\rates;
use App\Models\citiescounties;
use App\Models\customers;
use App\Models\tracker;
use Concerns\InteractsWithInput;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;


class RatesController extends Controller
{
    /**
     * Get the bearer token from the request headers.
     *
     * @return string|null
    */
    
    //function gets the bearer token string from the header of the request
    public function bearerToken($request){
        $header = $request->header('Authorization', '');   
        if (Str::startsWith($header, 'Bearer ')) {
                    return Str::substr($header, 7);
        }
    }

    /*
    *Function determines the tax rate or the amount of tax to charge based. 
    *This function also performs a auth check based on a bearer token.
    */
    public function SalesTaxDeterminer($request){
        //find county based on request info
        $county = citiescounties::select('county')->where('state', '=', $request->state)
            ->whereRaw('LOWER(city) = ?', strtolower($request->city))
            ->first();

        //use county (found above) and request info to find the tax rate 
        $rate = rates::select('rate')
            ->where('state', '=',  $request->state)
            ->where('zip_code', '=', $request->zipCode)
            ->whereRaw('LOWER(county) = ?', strtolower($county->county))
            ->first();

        return array('rate' => $rate->rate, 'county' => $county->county);
    }

    public function bear($request){
        //get bearer token from request
        $bear = $this->bearerToken($request);

        //compare bearer token with registered tokens
        $token = \Laravel\Sanctum\PersonalAccessToken::findToken($bear);
        $user = $token->tokenable;
        return $user;
    }

    public function Determination($request, $rate){
        $rState = $request->state;
        $state = customers::select($rState)->first();

        if(Auth::user()->premium_customer == 1 && $state->$rState == 1 && $request->amount != null){
            $taxAmount = $request->amount * ($rate/100);
            $response = $request->amount * ($rate/100);
        }
        elseif(Auth::user()->premium_customer == 1 && $state->$rState == 1 && $request->amount == null){
            $taxAmount = 0;
            $response = $rate;
        }
        elseif(Auth::user()->premium_customer == 1 && $state->$rState == 0){
            $taxAmount = 0;
            $response = 0;
        }

        elseif($request->amount != null){
            $response = $request->amount * ($rate/100);
            $taxAmount = $request->amount * ($rate/100);
            
        }
        else{
            $response = $rate;
            $taxAmount = 0;
        }
        return array('response' => $response, 'taxAmount' => $taxAmount);
    }

    //The standard funtion used to generate the tax rate or amount owed
    public function BasicRate(Request $request){

        //call function that determines the tax rate or the amount of tax to be collected. This function also performs a auth check based on a bearer token.
        $rate = $this->SalesTaxDeterminer($request);

        //verify registered user
        $user = $this->bear($request);

        //Determination function call
        $det = $this->Determination($request, $rate['rate']);

        //tracker call
        $tracker = parent::SaveRequest($request->all(), "Rate", $rate['rate'], $request->amount, $det['taxAmount'], $rate['county'], $request->ip(), $user);

        $rState = $request->state;
        $state = customers::select($rState)->first();

        //if there was no problem, pass along result
        if($tracker == true){
            return $det['response'];
        }
        else{
            return ["Please registser as a customer and pass your token in the request"];
        }
    }

    //This function saves the amount to the state
    public function RateTracker(Request $request){

        
        //call function that determines the tax rate or the amount of tax to be collected. This function also performs a auth check based on a bearer token.
        $rate = $this->SalesTaxDeterminer($request);

        //verify registered user
        $user = $this->bear($request);

        $det = $this->Determination($request, $rate['rate']);

        //tracker call
        $tracker = parent::SaveRequest($request->all(), "Track", $rate['rate'], $request->amount, $det['taxAmount'], $rate['county'], $request->ip(), $user);

        $totalSales = $request->state . "_total_sales";
        $numOfSales = $request->state . "_num_of_sales";

        $customer = customers::select($request->state, $request->state . "_total_sales", $request->state . "_num_of_sales")->where('customer_id', '=', $user->id)->first();

        $stateTotalSales = $customer->$totalSales;
        $stateNumOfSales = $customer->$numOfSales;

        $stateTotalSales += $request->amount;
        $stateNumOfSales++;

        /**NEED TO MAKE DETERMINATIONS ABOUT THE TRANSACTION -- IF IT HAS NOT PUT THE CUSTOMER OVER THE LIMIT
         * FOR A STATE FOR EXAMPLE.
         */
        customers::where('customer_id', '=', $user->id)
                    ->update(array($totalSales => $stateTotalSales,
                                    $numOfSales => $stateNumOfSales));

    }

    public function EditTransaction(Request $request){
        
        // dd($request->taxed);
        $original = tracker::where('user_id', '=', Auth::user()->id)
                            ->where('id', '=', $request->editId)
                            ->first();

        $state = $original->state . "_total_sales";

        $customer = customers::select($original->state, $original->state . "_total_sales", $original->state . "_num_of_sales")->where('customer_id', '=', Auth::user()->id)->first();

        //transaction amount calculations
        $newSales = (float) $customer->{$original->state . "_total_sales"} - (float) - $original->amount + (float) str_replace(",", "", $request->editTranAmount);

        //tax amount calculations
        


        tracker::where('user_id', '=', Auth::user()->id)
                ->where('id', '=', $request->editId)
                ->update(array('amount' => str_replace(",", "", $request->editTranAmount),
                            'tax_amount' => str_replace(",", "", $request->editTax)));

        customers::where('customer_id', '=', Auth::user()->id)
                // ->where('id', '=', $request->editId)
                ->update(array($state => str_replace(",", "", $newSales)

        ));

    }

}