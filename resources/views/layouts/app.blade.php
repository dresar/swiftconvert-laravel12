<!DOCTYPE html>
{{-- Tambahkan class="dark" untuk memaksa dark mode --}}
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        {{-- ... (meta tags, title, fonts) ... --}}
        <title>{{ config('settings.app_name', 'SwiftConvert') }} - @yield('title', 'File Converter')</title>

        {{-- ... (fonts) ... --}}

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('styles')
    </head>
    {{-- Ganti background body --}}
    <body class="font-sans antialiased bg-gray-100 dark:bg-slate-900">
        <div class="min-h-screen flex flex-col">
            {{-- Navbar (Sesuaikan jika perlu agar lebih cocok dark mode) --}}
            <nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-slate-700 shadow-sm">
                {{-- ... (Isi Navbar seperti sebelumnya) ... --}}
                {{-- Pastikan logo/nama aplikasi dan link terlihat bagus di dark mode --}}
                 <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                             <div class="shrink-0 flex items-center">
                                <a href="{{ route('converter.index') }}">
                                     {{-- Tampilkan Logo atau Nama (Pastikan kontras di dark) --}}
                                    @if(config('settings.app_logo') && Storage::disk('public')->exists(config('settings.app_logo')))
                                        <img src="{{ Storage::url(config('settings.app_logo')) }}" alt="{{ config('settings.app_name', 'Logo') }}" class="block h-9 w-auto">
                                    @else
                                         <span class="text-xl font-semibold text-gray-800 dark:text-gray-100">{{ config('settings.app_name', 'SwiftConvert') }}</span>
                                    @endif
                                </a>
                            </div>
                        </div>
                         {{-- Navigasi Kanan (Login/Register/Admin) --}}
                        <div class="hidden sm:flex sm:items-center sm:ms-6">
                             {{-- ... (link auth seperti sebelumnya, pastikan text color dark mode ok) ... --}}
                            @auth
                                 @if(Auth::user()->is_admin)
                                    <a href="{{ route('admin.dashboard') }}" class="ms-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Admin Dashboard</a>
                                 @endif
                                 {{-- Dropdown User jika diperlukan --}}
                                  <form method="POST" action="{{ route('logout') }}" class="ms-4">
                                     @csrf
                                     <a href="{{ route('logout') }}"
                                        class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                                        onclick="event.preventDefault(); this.closest('form').submit();">
                                         Log Out
                                     </a>
                                 </form>
                            @else
                                <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Log in</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="ms-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Register</a>
                                @endif
                            @endauth
                        </div>
                         {{-- Hamburger Menu --}}
                         {{-- ... (Kode hamburger seperti sebelumnya) ... --}}
                    </div>
                </div>
                {{-- Responsive Navigation Menu --}}
                {{-- ... (Kode menu mobile seperti sebelumnya) ... --}}
            </nav>

            {{-- Main Content - flex-grow agar footer menempel di bawah --}}
            <main class="flex-grow">
                @yield('content')
            </main>

            {{-- Footer (Sesuaikan background dan text color) --}}
            <footer class="bg-white dark:bg-gray-800 border-t border-gray-100 dark:border-slate-700 py-4 mt-auto">
                 <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm text-gray-500 dark:text-gray-400">
                     © {{ date('Y') }} {{ config('settings.app_name', 'SwiftConvert') }}. All rights reserved.
                 </div>
            </footer>
        </div>
        @stack('scripts')
    </body>
</html>