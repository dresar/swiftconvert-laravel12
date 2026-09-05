<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ConversionHistory extends Model
{
    use HasFactory;

    protected $table = 'conversion_histories';

    protected $fillable = [
        'original_filename',
        'original_filesize',
        'original_mimetype',
        'output_format',
        'converted_filename',
        'storage_path',
        'status',
        'error_message',
        'ip_address',
        'converted_at',
        // 'user_id', // Uncomment jika ada relasi user
    ];

    protected $casts = [
        'original_filesize' => 'integer',
        'converted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function getConvertedFileUrlAttribute(): ?string
    {
        if ($this->status === 'success' && $this->storage_path && Storage::disk('public')->exists($this->storage_path)) {
            return Storage::disk('public')->url($this->storage_path);
        }
        return null;
    }

    // public function user() // Uncomment jika ada relasi user
    // {
    //     return $this->belongsTo(User::class);
    // }
}