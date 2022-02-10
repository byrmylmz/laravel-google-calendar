<?php

namespace App\Http\Controllers;
use App\Services\Google;
use App\Models\GoogleAccount;




use Illuminate\Http\Request;

class GoogleAccountController extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('accounts', [
            'accounts' => auth()->user()->googleAccounts,
        ]);
    }

    public function createEvent(Request $request, Google $google) {
       
        $informations= auth()->user()->googleAccounts()->first()->token;
        $service = $google->connectUsing($informations['access_token'])->service('calendar');
       // $this->client->setAccessToken($informations['access_token']);
       // $service = new Google_Service_Calendar($this->client);


    }

    public function store(Request $request, Google $google)
    {
        if (! $request->has('code')) {
            return redirect($google->createAuthUrl());
        }
       
        $google->authenticate($request->get('code'));

        $account = $google->service('Oauth2');
        $userInfo = $account->userinfo->get();

        //$infoArray=json_encode($userInfo);
        /**///return $userInfo->email;
        //return $infoArray->email;
        //print_r($userInfo);

        auth()->user()->googleAccounts()->updateOrCreate(
            [
                'google_id' => $userInfo->id,
            ],
            [
                'name' =>$userInfo->email,
                'token' => $google->getAccessToken(),
            ]
        );

        return redirect()->route('google.index');
        
    }

    public function destroy(GoogleAccount $googleAccount, Google $google)
    {
        $googleAccount->calendars->each->delete();

        $googleAccount->delete();

        $google->revokeToken($googleAccount->token);

        return redirect()->back();
    }

  
}