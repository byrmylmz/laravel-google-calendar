<?php

namespace App\Http\Controllers;
use App\Services\Google;


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
        $account = $google->service('Plus')->people->get('me');

        auth()->user()->googleAccounts()->updateOrCreate(
            [
                'google_id' => $account->id,
            ],
            [
                'name' => head($account->emails)->value,
                'token' => $google->getAccessToken(),
            ]
        );

        return redirect()->route('google.index');
    }

    public function destroy(GoogleAccount $googleAccount)
    {
        // TODO:
        // - Revoke the authentication token.
        // - Delete the Google Account.
    }
}