<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Service;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['phone' => '081111111111'],
            [
                'name' => 'Admin Neatify',
                'email' => 'admin@neatify.com',
                'password' => Hash::make('123456'),
                'alamat' => 'Kantor Neatify',
                'role' => 'admin',
                'saldo' => 0,
                'poin' => 0
            ]
        );

        User::updateOrCreate(
            ['phone' => '082222222222'],
            [
                'name' => 'Adara',
                'email' => 'user@neatify.com',
                'password' => Hash::make('123456'),
                'alamat' => 'Banjarnegara',
                'role' => 'user',
                'saldo' => 750000,
                'poin' => 1200
            ]
        );

        $services = [
            [
                'nama_layanan' => 'Cuci Kering',
                'deskripsi' => 'Pakaian dicuci dan dikeringkan dengan cepat.',
                'harga' => 7000,
                'satuan' => 'kg',
                'image' => 'cuci_kering.png'
            ],
            [
                'nama_layanan' => 'Cuci Setrika',
                'deskripsi' => 'Pakaian dicuci, dikeringkan, dan disetrika rapi.',
                'harga' => 10000,
                'satuan' => 'kg',
                'image' => 'cuci_setrika.png'
            ],
            [
                'nama_layanan' => 'Setrika Saja',
                'deskripsi' => 'Pakaian disetrika agar lebih rapi.',
                'harga' => 6000,
                'satuan' => 'kg',
                'image' => 'setrika.png'
            ],
            [
                'nama_layanan' => 'Cuci Kilat',
                'deskripsi' => 'Layanan laundry cepat untuk kebutuhan mendadak.',
                'harga' => 15000,
                'satuan' => 'kg',
                'image' => 'cuci_kilat.png'
            ],
            [
                'nama_layanan' => 'Laundry Sepatu',
                'deskripsi' => 'Membersihkan sepatu agar lebih segar dan bersih.',
                'harga' => 25000,
                'satuan' => 'pasang',
                'image' => 'sepatu.png'
            ],
            [
                'nama_layanan' => 'Laundry Bed Cover',
                'deskripsi' => 'Membersihkan bed cover, selimut, dan perlengkapan tidur.',
                'harga' => 30000,
                'satuan' => 'pcs',
                'image' => 'bedcover.png'
            ],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(
                ['nama_layanan' => $service['nama_layanan']],
                $service
            );
        }
    }
}