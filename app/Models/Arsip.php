<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Arsip extends Model
{
    use HasFactory;
    protected $table = 'arsips';

    protected $fillable = [
        'judul',
        'pengarang',
        'abstrak',
        'lokasi_rak',
        'lokasi_baris',
        'kategori',             // baru
        'thumbnail_path',
        'file_dokumen_path',    // baru
        'slug',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($arsip) {
            if (empty($arsip->slug)) {
                $arsip->slug = Str::slug($arsip->judul);
            }
        });
    }
}
