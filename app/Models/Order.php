<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'kode_order',
        'user_id',
        'service_id',
        'layanan',
        'berat',
        'catatan',
        'alamat',
        'total_harga',
        'ongkir',
        'diskon',
        'metode_pembayaran',
        'status_pembayaran',
        'status',
        'estimasi_selesai'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function service() {
        return $this->belongsTo(Service::class);
    }
    
}
