@extends('layouts.app')

@section('title', 'Konverter File Online Cepat & Mudah')

@section('content')
    {{-- Container utama dengan padding --}}
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">

        {{-- Kotak Konverter --}}
        <div class="max-w-3xl mx-auto bg-gray-800 dark:bg-slate-800 shadow-xl rounded-lg overflow-hidden border border-slate-700">
            <div class="p-6 md:p-10">

                {{-- Header di dalam kotak --}}
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold mb-2 text-gray-100 dark:text-white">{{ config('settings.app_name', 'SwiftConvert') }}</h1>
                    <h2 class="text-xl font-semibold mb-2 text-gray-200 dark:text-gray-200">Konverter File</h2>
                    <p class="text-sm text-gray-400 dark:text-gray-400">Unggah file Anda dan pilih format tujuan konversi.</p>
                </div>

                {{-- Area Notifikasi Sukses/Error --}}
                <div class="mb-6 space-y-4">
                     @if (session('success'))
                        <div class="p-4 bg-green-900/50 border border-green-700 text-green-200 rounded-md relative flex items-start" role="alert">
                             <svg class="flex-shrink-0 w-5 h-5 mr-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"> <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /> </svg>
                            <div>
                                <span class="font-medium">File berhasil dikonversi!</span>
                                 @if(session('converted_filename'))
                                     <a href="{{ route('converter.download', session('converted_filename')) }}" class="mt-1 block underline font-semibold text-green-300 hover:text-white">
                                         Unduh: {{ session('converted_filename') }}
                                     </a>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="p-4 bg-red-900/50 border border-red-700 text-red-200 rounded-md relative flex items-start" role="alert">
                             <svg class="flex-shrink-0 w-5 h-5 mr-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"> <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /> </svg>
                             <div>
                                <span class="font-medium">Terjadi Kesalahan:</span> {{ session('error') }}
                             </div>
                        </div>
                    @endif

                    @if ($errors->any())
                         <div class="p-4 bg-red-900/50 border border-red-700 text-red-200 rounded-md relative" role="alert">
                            <strong class="font-bold block mb-1">Oops! Ada beberapa masalah:</strong>
                            <ul class="list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                {{-- Form Konversi --}}
                <form method="POST" action="{{ route('converter.convert') }}" enctype="multipart/form-data" id="converter-form" class="space-y-6">
                    @csrf

                    {{-- Input File --}}
                    <div>
                        <label for="file" class="block font-medium text-sm text-gray-300 dark:text-gray-300 mb-2">1. Unggah File</label>
                        <label for="file" class="file-input-wrapper border-gray-600 dark:border-slate-600 bg-gray-700 dark:bg-slate-700/50 hover:border-indigo-500 dark:hover:border-indigo-500 transition duration-150 ease-in-out">
                            <input type="file" name="file" id="file" class="hidden" required>
                            {{-- Tombol Pilih File --}}
                            <span class="file-input-button bg-gray-600 dark:bg-slate-600 border-gray-500 dark:border-slate-500 text-gray-200 dark:text-gray-200 hover:bg-gray-500 dark:hover:bg-slate-500">Pilih File</span>
                            {{-- Teks Nama File --}}
                            <span class="file-input-text text-gray-400 dark:text-gray-400" id="file-chosen-text">Tidak ada file yang dipilih</span>
                        </label>
                        <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">Maksimum ukuran file: 20MB (Contoh).</p>
                    </div>

                    {{-- Pilih Format Output --}}
                    <div>
                        <label for="output_format" class="block font-medium text-sm text-gray-300 dark:text-gray-300 mb-2">2. Pilih Format Output</label>
                        <select name="output_format" id="output_format" class="block w-full border-gray-600 dark:border-slate-600 bg-gray-700 dark:bg-slate-700 text-gray-200 dark:text-gray-200 focus:border-indigo-500 dark:focus:border-indigo-500 focus:ring-indigo-500 dark:focus:ring-indigo-500 rounded-md shadow-sm appearance-none py-2 px-3 leading-tight focus:outline-none" required>
                            <option value="" disabled {{ old('output_format') ? '' : 'selected' }} class="text-gray-500">Pilih format...</option>
                            <option value="pdf" {{ old('output_format') == 'pdf' ? 'selected' : '' }}>PDF</option>
                            <option value="docx" {{ old('output_format') == 'docx' ? 'selected' : '' }}>DOCX (Word Document)</option>
                            <option value="txt" {{ old('output_format') == 'txt' ? 'selected' : '' }}>TXT (Plain Text)</option>
                            <option value="jpg" {{ old('output_format') == 'jpg' ? 'selected' : '' }}>JPG (Gambar)</option>
                            <option value="png" {{ old('output_format') == 'png' ? 'selected' : '' }}>PNG (Gambar)</option>
                            <option value="mp3" {{ old('output_format') == 'mp3' ? 'selected' : '' }}>MP3 (Audio)</option>
                            <option value="wav" {{ old('output_format') == 'wav' ? 'selected' : '' }}>WAV (Audio)</option>
                            {{-- Tambahkan format lain jika didukung backend --}}
                        </select>
                    </div>

                    {{-- Tombol Konversi --}}
                    <div class="pt-2">
                        <button type="submit" id="convert-button" class="w-full inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 focus:ring-offset-gray-800 dark:focus:ring-offset-slate-800 disabled:opacity-60 disabled:cursor-not-allowed transition ease-in-out duration-150">
                            Konversi File
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Bagian Tutorial dan Notifikasi (di luar kotak utama) --}}
        <div class="max-w-3xl mx-auto mt-8 md:mt-12 px-2">
             @include('partials._notification') {{-- Notifikasi akan memakai style dari file partial --}}
             @include('partials._tutorial')    {{-- Tutorial akan memakai style dari file partial --}}
        </div>

    </div>
@endsection

@push('styles')
{{-- Kita bisa tambahkan CSS tambahan khusus halaman ini jika perlu --}}
<style>
/* Style untuk pointer dropdown select agar terlihat di dark mode */
select {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%239ca3af' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 0.5rem center;
    background-repeat: no-repeat;
    background-size: 1.5em 1.5em;
    padding-right: 2.5rem;
}
.dark select {
     background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
}
</style>
@endpush