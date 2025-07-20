<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use App\Http\Middleware\CheckToken;

use App\Http\Controllers\Api\AuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login.index');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/login', function (\Illuminate\Http\Request $request) {
    $response = Http::post(url('http://localhost/instaapp_sevima/public/api/login'), $request->only('email', 'password'));

    if ($response->failed()) {
        return back()->withErrors(['email' => 'Email atau password salah']);
    }

    session(['token' => $response['token'], 'user' => $response['user']]);
    return redirect('/dashboard');
});

Route::post('/register', function (\Illuminate\Http\Request $request) {
    $response = Http::post(url('http://localhost/instaapp_sevima/public/api/register'), $request->all());

    if ($response->failed()) {
        return back()->withErrors(['email' => 'Gagal mendaftar. Coba lagi.']);
    }

    session(['token' => $response['token'], 'user' => $response['user']]);
    return redirect('/dashboard');
});

Route::post('/register', function (\Illuminate\Http\Request $request) {
    $controller = new AuthController();
    $response = $controller->register($request);

    if ($response->getStatusCode() !== 200) {
        return back()->withErrors(['email' => 'Gagal mendaftar. Coba lagi.']);
    }

    $data = $response->getData(true);

    session([
        'token' => $data['token'], 
        'user' => $data['user']
    ]);
    
    return redirect('/dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('check.token');

Route::get('/logout', function () {
    session()->flush();
    return redirect('/login');
});