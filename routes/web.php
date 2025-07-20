<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('check.token');

Route::get('/logout', function () {
    session()->flush();
    return redirect('/login');
});

Route::get('/posts/create', function () {
    return view('posts.create');
})->middleware('check.token');

Route::post('/posts', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'image' => 'required|image',
        'caption' => 'nullable|string',
    ]);

    $token = session('token');
    $file = $request->file('image');
    $filename = $file->getClientOriginalName();

    $response = Http::attach(
        'image', file_get_contents($file), $filename
    )->withToken($token)->post(url('http://localhost/instaapp_sevima/public/api/posts'), [
        'caption' => $request->caption,
        'allow_comment' => $request->has('allow_comment'),
    ]);

    if ($response->failed()) {
        return back()->withErrors(['msg' => 'Gagal upload post']);
    }

    return redirect('/dashboard')->with('success', 'Post berhasil diupload');
})->middleware('check.token');

Route::post('/posts/{id}/delete', function ($id) {
    Http::withToken(session('token'))->delete(url("http://localhost/instaapp_sevima/public/api/posts/$id"));
    return back();
})->middleware('check.token');

Route::post('/posts/{id}/edit', function ($id, \Illuminate\Http\Request $request) {
    Http::withToken(session('token'))->put(url("http://localhost/instaapp_sevima/public/api/posts/$id"), [
        'caption' => $request->caption,
        'allow_comment' => $request->has('allow_comment'),
    ]);
    return back();
})->middleware('check.token');

Route::post('/posts/{id}/like', function ($id) {
    $response = Http::withToken(session('token'))->post(url("http://localhost/instaapp_sevima/public/api/posts/$id/like"));
    return back();
})->middleware('check.token');

Route::post('/posts/{id}/comments', function ($id, \Illuminate\Http\Request $request) {
    $response = Http::withToken(session('token'))->post(url("http://localhost/instaapp_sevima/public/api/posts/$id/comments"), [
        'content' => $request->content,
    ]);

    return back();
})->middleware('check.token');

Route::post('/comments/{id}/delete', function ($id) {
    Http::withToken(session('token'))->delete(url("http://localhost/instaapp_sevima/public/api/comments/$id"));
    return back();
})->middleware('check.token');