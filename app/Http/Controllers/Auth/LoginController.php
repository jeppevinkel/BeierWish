<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirectToProvider($provider): \Symfony\Component\HttpFoundation\RedirectResponse|\Illuminate\Http\RedirectResponse
    {
        if ($provider === 'facebook') {
            return Socialite::driver($provider)->scopes(['email'])->redirect();
        }
        elseif ($provider === 'google') {
            return Socialite::driver($provider)->redirect();
        }

        return redirect()->back();
    }

    public function handleProviderCallback($provider)
    {
        $providerUser = Socialite::driver($provider)->stateless()->user();

//        dd($providerUser);

        $socialAccount = SocialAccount::where('provider_id', $providerUser->getId())->first();

        if ($socialAccount) {
            auth()->login($socialAccount->user);

            return redirect()->intended();
        }

        if (User::where('email', $providerUser->getEmail())->first()) {
            return redirect()->back()->with('error', 'Account with this email already exists');
        }

        $user = User::create([
            'name' => $providerUser->getName(),
            'email' => $providerUser->getEmail(),
            'vanity_url' => Str::slug($providerUser->getName()) . '-' . Str::random(4)]);

        $user->socialAccounts()->create([
            'provider_id' => $providerUser->getId(),
            'provider_name' => $provider,
            'email' => $providerUser->getEmail(),
            'name' => $providerUser->getName(),
            'avatar' => $providerUser->getAvatar()]);

        dd($user);
    }
}
