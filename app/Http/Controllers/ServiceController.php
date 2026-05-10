<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    // Menampilkan semua layanan (Ini yang akan muncul OTOMATIS di Postman saat GET)
    public function index()
    {
        $services = Service::all();
        return response()->json([
            'status' => 'success',
            'data' => $services
        ]);
    }

    // Menyimpan layanan baru (Ini yang datanya kamu KETIK MANUAL di Postman saat POST)
    public function store(Request $request)
    {
        // Validasi input agar tidak kosong
        $request->validate([
            'nama_layanan' => 'required',
            'deskripsi'    => 'required',
            'harga'        => 'required|integer',
            'satuan'       => 'required',
            'image'        => 'required'
        ]);

        $service = Service::create([
            'nama_layanan' => $request->nama_layanan,
            'deskripsi'    => $request->deskripsi,
            'harga'        => $request->harga,
            'satuan'       => $request->satuan,
            'image'        => $request->image,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Layanan berhasil ditambahkan!',
            'data'    => $service
        ]);
    }

    // Melihat detail satu layanan saja
    public function show($id)
    {
        $service = Service::find($id);
        if (!$service) {
            return response()->json(['message' => 'Layanan tidak ditemukan'], 404);
        }
        return response()->json($service);
    }
}