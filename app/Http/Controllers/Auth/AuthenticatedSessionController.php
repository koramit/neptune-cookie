<?php

namespace App\Http\Controllers\Auth;

use App\APIs\SubHannahAPI;
use App\Contracts\AuthenticationAPI;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Inertia\Inertia;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Inertia\Respons
     */
    public function create()
    {
        return Inertia::render('Auth/Login');
    }

    public function store()
    {
        Request::validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        // $user = User::whereLogin(Request::input('login'))->first();

        $api = new SubHannahAPI;

        $data = $api->authenticate(Request::input('login'), Request::input('password'));

        if (! $data['found']) {
            return back()->withErrors([
                'login' => $data['message'],
            ]);
        }

        if (! $user = User::whereOrgId($data['org_id'])->first()) {
            $user = $this->autoRegister($data);
        }

        Auth::login($user);

        return Redirect::intended(route($user->home_page));



        // Session::put('profile', $data);

        // return Redirect::route('register');
    }

    public function update()
    {
        return ['active' => true];
    }

    public function destroy()
    {
        Auth::guard('web')->logout();

        Request::session()->invalidate();

        Request::session()->regenerateToken();

        return Redirect::route('login');
    }

    // protected function storeAvatarUser()
    // {
    //     if (Auth::attempt(Request::only(['login', 'password']), )) {
    //         return Redirect::intended(route(Auth::user()->home_page));
    //     }

    //     return back()->withErrors([
    //         'login' => __('auth.failed'),
    //     ]);
    // }

    protected function autoRegister($data)
    {
        $user =  User::create([
            'name' => $data['username'],
            'login' => $data['username'],
            'full_name' => $data['name'],
            'org_id' => $data['org_id'],
            'password' => Hash::make(Str::random(64)),
        ]);

        return User::find($user->id);
    }
}
