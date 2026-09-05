@extends('layouts.admin')

@section('title', 'Dashboard')

@section('header')
    {{ __('Dashboard Admin') }}
@endsection

@section('content')
    <p class="mb-6">Selamat datang di panel admin, {{ Auth::user()->name }}!</p>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg p-5 border border-gray-200 dark:border-gray-700">
            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Konversi</dt>
            <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900 dark:text-gray-100">{{ $totalConversions ?? 0 }}</dd>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg p-5 border border-green-300 dark:border-green-700">
            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Konversi Sukses</dt>
            <dd class="mt-1 text-3xl font-semibold tracking-tight text-green-600 dark:text-green-400">{{ $successfulConversions ?? 0 }}</dd>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg p-5 border border-red-300 dark:border-red-700">
            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Konversi Gagal</dt>
            <dd class="mt-1 text-3xl font-semibold tracking-tight text-red-600 dark:text-red-400">{{ $failedConversions ?? 0 }}</dd>
        </div>

         <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg p-5 border border-blue-300 dark:border-blue-700">
            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Konversi Hari Ini</dt>
            <dd class="mt-1 text-3xl font-semibold tracking-tight text-blue-600 dark:text-blue-400">{{ $todayConversions ?? 0 }}</dd>
        </div>
    </div>

    <div class="mt-8 p-6 bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 rounded-lg">
        <h3 class="text-lg font-semibold text-blue-800 dark:text-blue-200 mb-2">Akses Cepat</h3>
        <div class="flex flex-wrap gap-4">
            <a href="{{ route('admin.history.index') }}" class="inline-block px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded hover:bg-blue-700 transition duration-150">Lihat Riwayat Konversi</a>
            <a href="{{ route('admin.settings.edit') }}" class="inline-block px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded hover:bg-gray-700 transition duration-150">Pengaturan Aplikasi</a>
        </div>
    </div>

@endsection