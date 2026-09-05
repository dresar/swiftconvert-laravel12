<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('conversion_histories', function (Blueprint $table) {
            $table->id(); // Kolom ID unik otomatis (Primary Key)
            $table->string('original_filename'); // Nama file asli yang diupload
            $table->unsignedBigInteger('original_filesize')->nullable(); // Ukuran file asli dalam bytes
            $table->string('original_mimetype')->nullable(); // Tipe MIME file asli (misal: image/jpeg)
            $table->string('output_format'); // Format tujuan yg dipilih (misal: 'pdf', 'png')
            $table->string('converted_filename')->nullable(); // Nama file setelah konversi (jika berhasil)
            $table->string('storage_path')->nullable(); // Path penyimpanan file hasil konversi
            $table->enum('status', ['pending', 'processing', 'success', 'failed'])->default('pending'); // Status konversi
            $table->text('error_message')->nullable(); // Pesan error jika gagal
            // $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Jika ada fitur login user, link ke tabel users
            $table->ipAddress('ip_address')->nullable(); // IP Address user yg melakukan konversi
            $table->timestamp('converted_at')->nullable(); // Waktu selesai konversi
            $table->timestamps(); // Kolom created_at dan updated_at otomatis
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversion_histories');
    }
};