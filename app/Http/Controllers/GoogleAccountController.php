<?php

namespace App\Http\Controllers;
use App\Services\Google;
use App\Models\GoogleAccount;




use Illuminate\Http\Request;

class GoogleAccountController extends Controller
{
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

    public function destroy(GoogleAccount $googleAccount)
    {
        $googleAccount->delete();
        // Event though it has been deleted from our database,
         // we still have access to $googleAccount as an object in memory.
       $google->revokeToken($googleAccount->token);
       return redirect()->back();
    }
}