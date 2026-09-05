<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\ConversionHistory;
use Carbon\Carbon;
use Throwable; // Import Throwable

class ConverterController extends Controller
{
    public function index()
    {
        return view('converter.index');
    }

    public function convert(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|max:20480', // Maks 20MB contoh
            'output_format' => 'required|string|in:pdf,docx,txt,jpg,png,mp3,wav', // Sesuaikan format yg didukung
        ]);

        if ($validator->fails()) {
            return redirect()->route('converter.index')
                        ->withErrors($validator)
                        ->withInput();
        }

        $file = $request->file('file');
        $outputFormat = strtolower($request->input('output_format'));
        $originalFilename = $file->getClientOriginalName();
        $originalMimetype = $file->getMimeType();
        $originalFilesize = $file->getSize();
        $baseFilename = pathinfo($originalFilename, PATHINFO_FILENAME);
        $uniqueSuffix = Str::random(8);
        $uploadFilename = Str::slug($baseFilename) . '-' . $uniqueSuffix . '.' . $file->getClientOriginalExtension();
        $convertedFilename = Str::slug($baseFilename) . '-' . $uniqueSuffix . '_converted.' . $outputFormat;

        $history = null; // Inisialisasi variabel history

        try {
            $uploadPath = $file->storeAs('uploads', $uploadFilename, 'public');

            if (!$uploadPath) {
                 throw new \Exception('Gagal menyimpan file upload.');
            }

            $history = ConversionHistory::create([
                'original_filename' => $originalFilename,
                'original_filesize' => $originalFilesize,
                'original_mimetype' => $originalMimetype,
                'output_format' => $outputFormat,
                'status' => 'processing',
                'ip_address' => $request->ip(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // --- LOGIKA KONVERSI SEBENARNYA HARUS DITEMPATKAN DI SINI ---
            // Ini hanya simulasi: membuat file teks dummy sebagai hasil
            sleep(2); // Simulasi waktu proses

            $convertedContent = "Ini adalah hasil konversi simulasi dari {$originalFilename} ke format .{$outputFormat}.";
            $convertedStoragePath = 'converted/' . $convertedFilename;

            $simpanHasilKonversi = Storage::disk('public')->put($convertedStoragePath, $convertedContent);

            if (!$simpanHasilKonversi) {
                throw new \Exception('Gagal menyimpan file hasil konversi.');
            }
            // --- AKHIR SIMULASI KONVERSI ---

            $history->update([
                'converted_filename' => $convertedFilename,
                'storage_path' => $convertedStoragePath,
                'status' => 'success',
                'converted_at' => now(),
                'updated_at' => now(),
            ]);

             // Hapus file asli setelah konversi berhasil (opsional)
            // Storage::disk('public')->delete($uploadPath);

            return redirect()->route('converter.index')
                             ->with('success', 'File berhasil dikonversi!')
                             ->with('converted_filename', $convertedFilename);

        } catch (Throwable $e) { // Tangkap semua jenis error/exception
            if ($history) {
                $history->update([
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                    'updated_at' => now(),
                ]);
            }

            // Hapus file upload jika konversi gagal
            if (isset($uploadPath) && Storage::disk('public')->exists($uploadPath)) {
                 Storage::disk('public')->delete($uploadPath);
            }

            return redirect()->route('converter.index')
                             ->with('error', 'Terjadi kesalahan saat konversi: ' . $e->getMessage());
        }
    }


    public function download(string $filename)
    {
         // Cari berdasarkan nama file hasil konversi
        $history = ConversionHistory::where('converted_filename', $filename)
                                     ->where('status', 'success')
                                     ->first();

        if (!$history || !$history->storage_path) {
             return redirect()->route('converter.index')->with('error', 'File tidak ditemukan atau belum selesai dikonversi.');
        }

        $path = $history->storage_path;

        if (!Storage::disk('public')->exists($path)) {
            return redirect()->route('converter.index')->with('error', 'File fisik tidak ditemukan di penyimpanan.');
        }

        // Gunakan nama asli saat download (atau nama hasil konversi)
        // $downloadName = $history->original_filename; // Jika ingin nama asli
        $downloadName = $history->converted_filename; // Jika ingin nama hasil konversi

        return Storage::disk('public')->download($path, $downloadName);
    }
}