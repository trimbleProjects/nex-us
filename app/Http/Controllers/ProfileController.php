<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use App\Models\User;
use App\Models\customers;
use App\Models\tracker;
use App\Models\personal_access_tokens;
use App\Http\Controllers\api\v1\RatesController;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response
     */
    public function edit(Request $request )
    {
        $token = "";
        // dd($request->all());
        if(session('CreateToken') == true){
            $request->user()->tokens->each(function($token, $key) {
                $token->delete();
            });
            $token = $request->user()->createToken('token', ['create', 'update', 'delete']);
            
            $token = $token->plainTextToken;
            session(['CreateToken' => false]);
        }
        else{
            $token = "";
        }

        $arr = User::get();
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
            'token' => $token,
        ]);
        // ->withViewData(['bear' => $token]);
    }

    public function CreateToken(Request $request){
        // $request->merge(['CreateToken' => true]);
        session(['CreateToken' => true]);
        // $this->edit($request);
        return Redirect::route('profile.edit');
    }

    /**
     * Update the user's profile information.
     *
     * @param  \App\Http\Requests\ProfileUpdateRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProfileUpdateRequest $request)
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit');
    }

    /**
     * Delete the user's account.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    Public function Nexus(Request $request){
        

        $nexus = customers::where('customer_id', '=', $request->user()->id)->first();
        $states = array("AL"=>"Alaska", "AK"=>"Arkansas", "AZ"=>"Arizona", "AR"=>"Arkansas", "CA"=>"California", "CO"=>"Colorado", "CT"=>"Connecticut", "DE"=>"Delaware", "FL"=>"Florida", "GA"=>"Georgia", "HI"=>"Hawaii", "ID"=>"Idaho", "IL"=>"Illinois", "IN"=>"Indiana", "IA"=>"Iowa", "KS"=>"Kansas", "KY"=>"Kentucky", "LA"=>"Louisiana", "ME"=>"Maine", "MD"=>"Maryland", "MA"=>"Massachusetts", "MI"=>"Michigan", "MN"=>"Minnesota", "MS"=>"Mississippi", "MO"=>"Missouri", "MT"=>"Montana", "NE"=>"Newbraska", "NV"=>"Nevada", "NH"=>"New Hampshire", "NJ"=>"New Jersey", "NM"=>"New Mexico", "NY"=>"New York", "NC"=>"North Carolina", "ND"=>"North Dakota", "OH"=>"Ohio", "OK"=>"Oklahoma", "OR"=>"Oregon", "PA"=>"Pennsylvania", "RI"=>"Rhode Island", "SC"=>"South Carolina", "SD"=>"South Dakota", "TN"=>"Tennessee", "TX"=>"Texas", "UT"=>"Utah", "VT"=>"Vermont", "VA"=>"Virginia", "WA"=>"Washington", "WV"=>"West Virginia", "WI"=>"Wisconsin", "WY"=>"Wyoming");

        // $states = array("OH" => array("OH_total_sales", "OH_num_of_sales"));

        // return view("/Nexus", ['nexus' => $nexus, 'states' => $states]);

        $infoArr = array();

        $stateTotalTax = array();
        foreach(array_reverse($states) as $key => $val){
            $stateTotalTax[$key] =  tracker::where('user_id',  '=' , Auth::user()->id)
                                    ->where('state', '=', $key)
                                    ->where('type', '=', 'track')
                                    ->orderby('state', 'desc')
                                    ->sum('tax_amount');
        }

        foreach(array_reverse($states) as $key => $val){
            foreach($stateTotalTax as $sKey => $state){
                if($key == $sKey){
                    $tempArr = array($val => 
                                array( $val . " Total Sales: " => "$" . $nexus->{$key . '_total_sales'}, 
                                    $val . " Number of Sales:" => $nexus->{$key . "_num_of_sales"},
                                    'Total Tax' => $state));
                                    $infoArr = array_merge($tempArr, $infoArr);
                }
            }
            
        }

        return Inertia::render('Nexus', [
            // 'states' => $states,
            'nexus' => $infoArr,
        ]);
    }

    public function NexusState(Request $request, $state){

        $states = array("AL"=>"Alaska", "AK"=>"Arkansas", "AZ"=>"Arizona", "AR"=>"Arkansas", "CA"=>"California", "CO"=>"Colorado", "CT"=>"Connecticut", "DE"=>"Delaware", "FL"=>"Florida", "GA"=>"Georgia", "HI"=>"Hawaii", "ID"=>"Idaho", "IL"=>"Illinois", "IN"=>"Indiana", "IA"=>"Iowa", "KS"=>"Kansas", "KY"=>"Kentucky", "LA"=>"Louisiana", "ME"=>"Maine", "MD"=>"Maryland", "MA"=>"Massachusetts", "MI"=>"Michigan", "MN"=>"Minnesota", "MS"=>"Mississippi", "MO"=>"Missouri", "MT"=>"Montana", "NE"=>"Newbraska", "NV"=>"Nevada", "NH"=>"New Hampshire", "NJ"=>"New Jersey", "NM"=>"New Mexico", "NY"=>"New York", "NC"=>"North Carolina", "ND"=>"North Dakota", "OH"=>"Ohio", "OK"=>"Oklahoma", "OR"=>"Oregon", "PA"=>"Pennsylvania", "RI"=>"Rhode Island", "SC"=>"South Carolina", "SD"=>"South Dakota", "TN"=>"Tennessee", "TX"=>"Texas", "UT"=>"Utah", "VT"=>"Vermont", "VA"=>"Virginia", "WA"=>"Washington", "WV"=>"West Virginia", "WI"=>"Wisconsin", "WY"=>"Wyoming");

        $state = array_search($state, $states);

        $nexus = customers::select($state, $state. "_total_sales", $state . "_num_of_sales")
                            ->where('customer_id',  '=' , Auth::user()->id)
                            ->first();
        
        $stateTotalTax = tracker::where('user_id',  '=' , Auth::user()->id)
                                ->where('state', '=', $state)
                                ->where('type', '=', 'track')
                                ->orderby('state', 'desc')
                                ->sum('tax_amount');

        $infoArr = array($state =>  
                        array( "Total Sales: " => "$" . number_format($nexus->{$state . '_total_sales'},2), 
                                $state . " Number of Sales:" => $nexus->{$state . "_num_of_sales"},
                                'Total Tax' => $stateTotalTax));
        

        $transactions = tracker::where('user_id',  '=' , Auth::user()->id)
                                ->where('amount', '>', 0)
                                ->where('state', '=', $state)
                                ->where('type', '=', 'track')
                                ->orderby('created_at', 'desc')
                                ->get();
                                // dd($transactions);

        return Inertia::render('IndivdualState', [
            'nexus' => $infoArr,
            'transactions' => $transactions,
            // 'taxTotal' => $taxTotal,
        ]);

    }
    public function AmountLookup(Request $request){
        dd($request->all());
        $rc = new RatesController();
        $result = $rc->SalesTaxDeterminer($request);
        // $result = $rc::SalesTaxDeterminer($request->all());
        // dd($result);
        $result = "$" . ($result['rate']/100) * $request->amount;

        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user(),
            ],
            'flash' => [
                'message' => session($result)
            ]
        ]);
        return redirect()->route('dashboard')->with('message', $result);
    }
}
