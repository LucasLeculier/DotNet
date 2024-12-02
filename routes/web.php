<?php

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/auth/redirect', function () {
    return Socialite::driver('microsoft')->redirect();
});


Route::get('/auth/callback', function () {
    $user = Socialite::driver('microsoft')->user();

    // Gérer l'utilisateur (par exemple, créer un compte ou connecter l'utilisateur)
    $existingUser = App\Models\User::where('email', $user->email)->first();

    if ($existingUser) {
        Auth::login($existingUser);
    } else {
        $newUser = App\Models\User::create([
            'name' => $user->name,
            'email' => $user->email,
            'password' => bcrypt(Str::random(16)), // Utiliser un mot de passe généré
        ]);
        Auth::login($newUser);
    }

    return redirect('/dashboard');
});