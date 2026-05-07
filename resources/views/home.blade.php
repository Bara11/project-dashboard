@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<div class="grid grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl p-5 border border-gray-200">
        <p class="text-xs text-gray-400 mb-1">Total Perangkat</p>
        <p class="text-2xl font-semibold text-gray-800">0</p>
    </div>
    <div class="bg-white rounded-xl p-5 border border-gray-200">
        <p class="text-xs text-gray-400 mb-1">Online</p>
        <p class="text-2xl font-semibold text-green-600">0</p>
    </div>
    <div class="bg-white rounded-xl p-5 border border-gray-200">
        <p class="text-xs text-gray-400 mb-1">Offline</p>
        <p class="text-2xl font-semibold text-red-500">0</p>
    </div>
    <div class="bg-white rounded-xl p-5 border border-gray-200">
        <p class="text-xs text-gray-400 mb-1">Total Node</p>
        <p class="text-2xl font-semibold text-indigo-600">0</p>
    </div>
</div>
<div class="bg-white rounded-xl border border-gray-200 p-6">
    <h2 class="font-medium text-gray-800 mb-1">Selamat datang, {{ auth()->user()->name }}!</h2>
    <p class="text-sm text-gray-500">Gunakan menu di sidebar untuk mengelola perangkat IoT Anda.</p>
</div>
@endsection
