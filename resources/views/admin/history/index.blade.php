@extends('layouts.admin')

@section('title', 'Riwayat Konversi')

@section('header')
    {{ __('Riwayat Konversi') }}
@endsection

@section('content')

    <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-md border border-gray-200 dark:border-gray-600">
        <form method="GET" action="{{ route('admin.history.index') }}">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cari (Nama File/IP)</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Masukkan nama file atau IP..." class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                <div>
                     <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Filter Status</label>
                     <select name="status" id="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                         <option value="">Semua Status</option>
                         @foreach($statuses ?? [] as $status)
                             <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                         @endforeach
                     </select>
                </div>
                <div class="self-end">
                     <button type="submit" class="w-full md:w-auto inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Filter / Cari
                    </button>
                     <a href="{{ route('admin.history.index') }}" class="ml-2 w-full md:w-auto mt-2 md:mt-0 inline-flex justify-center items-center px-4 py-2 border border-gray-300 dark:border-gray-500 text-sm font-medium rounded-md shadow-sm text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-600 hover:bg-gray-50 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <div class="overflow-x-auto shadow-md sm:rounded-lg">
        <table class="table min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">File Asli</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Output</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Waktu</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">IP Address</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($histories as $history)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $history->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                             <span title="{{ $history->original_filename }}" class="truncate block max-w-xs">{{ Str::limit($history->original_filename, 35) }}</span>
                             <small class="block text-gray-500 dark:text-gray-400">{{ \Illuminate\Support\Number::fileSize($history->original_filesize ?? 0, precision: 2) }}</small>
                         </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 uppercase">{{ $history->output_format }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @switch($history->status)
                                    @case('success') bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 @break
                                    @case('failed') bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 @break
                                    @case('processing') bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 @break
                                    @default bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200
                                @endswitch
                            ">
                                {{ ucfirst($history->status) }}
                             </span>
                             @if($history->status == 'failed' && $history->error_message)
                                 <span title="{{ $history->error_message }}" class="ml-1 cursor-help text-red-500">(?)</span>
                             @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            <span title="{{ $history->created_at }}">
                                {{ $history->created_at->diffForHumans() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $history->ip_address }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            @if($history->status == 'success' && $history->converted_file_url)
                                <a href="{{ $history->converted_file_url }}" target="_blank" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 mr-2">Unduh</a>
                            @endif
                             <form action="{{ route('admin.history.destroy', $history->id) }}" method="POST" class="inline-block delete-history-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                            Tidak ada riwayat konversi yang ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $histories->links('vendor.pagination.tailwind') }} {{-- Pastikan view pagination tailwind ada --}}
    </div>

@endsection