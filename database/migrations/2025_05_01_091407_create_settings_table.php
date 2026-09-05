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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // Kunci unik untuk pengaturan (misal: 'app_name', 'app_logo')
            $table->text('value')->nullable(); // Nilai pengaturan (bisa nama, path file, dll)
            $table->timestamps(); // created_at dan updated_at
        });

        // Tambahkan data default awal (opsional tapi bagus)
        \Illuminate\Support\Facades\DB::table('settings')->insert([
            ['key' => 'app_name', 'value' => 'SwiftConvert', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'app_logo', 'value' => null, 'created_at' => now(), 'updated_at' => now()], // Path logo bisa diisi nanti oleh admin
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};