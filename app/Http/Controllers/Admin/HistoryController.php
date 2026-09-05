<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ConversionHistory;
use Illuminate\Support\Facades\Storage;


class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = ConversionHistory::latest();

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('search')) {
            $searchTerm = '%' . $request->input('search') . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('original_filename', 'like', $searchTerm)
                  ->orWhere('converted_filename', 'like', $searchTerm)
                  ->orWhere('ip_address', 'like', $searchTerm);
            });
        }

        $histories = $query->paginate(15)->withQueryString();

        $statuses = ['pending', 'processing', 'success', 'failed'];

        return view('admin.history.index', compact('histories', 'statuses'));
    }

    public function destroy(ConversionHistory $history) // Menggunakan Route Model Binding
    {
        try {
            // Hapus file fisik jika ada
            if ($history->storage_path && Storage::disk('public')->exists($history->storage_path)) {
                Storage::disk('public')->delete($history->storage_path);
            }
            // Hapus juga file upload asli jika masih ada (sesuaikan path jika perlu)
            // $uploadPath = 'uploads/' . Str::slug(pathinfo($history->original_filename, PATHINFO_FILENAME)) . ... ; // perlu cara rekonstruksi nama upload unik
            // if (Storage::disk('public')->exists($uploadPath)) {
            //     Storage::disk('public')->delete($uploadPath);
            // }

            $history->delete();
            return redirect()->route('admin.history.index')->with('success', 'Riwayat konversi berhasil dihapus.');

        } catch (\Exception $e) {
            return redirect()->route('admin.history.index')->with('error', 'Gagal menghapus riwayat: ' . $e->getMessage());
        }
    }

}