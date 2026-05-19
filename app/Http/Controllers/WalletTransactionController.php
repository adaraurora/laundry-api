<?php

namespace App\Http\Controllers;

use App\Models\WalletTransaction;

class WalletTransactionController extends Controller
{
    public function byUser($id)
    {
        $transactions = WalletTransaction::where('user_id', $id)
            ->latest()
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Riwayat dompet berhasil diambil',
            'data' => $transactions
        ]);
    }
}