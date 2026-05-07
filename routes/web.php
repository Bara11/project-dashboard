<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::livewire('/', 'pages::auth.login')->name('login');
Route::livewire('/admin/dashboard', 'pages::admin.idx')->name('admin')->middleware('auth');
Route::livewire('/admin/client','pages::admin.client.idx')->name('admin.client')->middleware('auth');
Route::livewire('/client','pages::client.idx')->name('client')->middleware('auth');

Route::get('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');
