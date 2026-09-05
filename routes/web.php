<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConverterController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HistoryController;
use App\Http\Controllers\Admin\SettingController;
use App\Models\ConversionHistory; // Diperlukan untuk Route Model Binding di destroy

Route::get('/', [ConverterController::class, 'index'])->name('converter.index');
Route::post('/convert', [ConverterController::class, 'convert'])->name('converter.convert');
Route::get('/download/{filename}', [ConverterController::class, 'download'])->name('converter.download');


require __DIR__.'/auth.php';


Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/history', [HistoryController::class, 'index'])->name('history.index');
    Route::delete('/history/{history}', [HistoryController::class, 'destroy'])->name('history.destroy');

    Route::get('/settings', [SettingController::class, 'edit'])->name('settings.edit');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');

});

// Fallback atau route lain jika diperlukan
// Route::fallback(function () {
//     return redirect('/');
// });