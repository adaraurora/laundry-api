<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    // TAMBAHKAN BARIS INI:
    protected $fillable = [
        'nama_layanan', 
        'deskripsi', 
        'harga', 
        'satuan', 
        'image'
    ];
}