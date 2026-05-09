<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'layanan',
        'berat',
        'total_harga',
        'status'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function byUser($user_id) {
       return Order::where('user_id', $user_id)->get();

       $request->validate([
            'user_id' => 'required|exists:users,id',
            'layanan' => 'required',
            'berat' => 'required|numeric|min:1'
        ]);
    }

    
}
