<?php

namespace App\Http\Controllers\Oauth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;

class OauthController extends Controller
{

    public function login()
    {
        return Socialite::driver('laravelpassport')->scopes(['view-user'])->redirect();
    }

    public function callback(): RedirectResponse
    {
        $user = Socialite::driver('laravelpassport')->user();

        // Store Access token
        session()->put('access_token', $user->token);

        // Let's create a new entry in our users table (or update if it already exists) with some information from the user
        $user = User::updateOrCreate([
            'name' => $user->name,
            'email' => $user->email,
        ]);

        // Logging the user in
        Auth::login($user);

        // Here, you should redirect to your app's authenticated pages (e.g. the user dashboard)
        return to_route('dashboard');
    }

    public function logout(): RedirectResponse
    {
        $this->oauthLogout();

        Auth::guard('web')->logout();

        session()->invalidate();

        session()->regenerateToken();

        return redirect('/');
    }

    protected function oauthLogout()
    {
        $accessToken = session()->get('access_token');
        $host = env('OAUTH_HOST');

        $response = Http::withHeaders([
            "Accept" => "application/json",
            "Authorization" => "Bearer " . $accessToken
        ])->get($host."/api/logout");

    }

}
