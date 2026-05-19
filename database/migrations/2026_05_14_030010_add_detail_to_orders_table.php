<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('kode_order')->unique()->nullable()->after('id');
            $table->foreignId('service_id')->nullable()->after('user_id')->constrained('services')->nullOnDelete();
            $table->text('catatan')->nullable()->after('berat');
            $table->text('alamat')->nullable()->after('catatan');
            $table->integer('ongkir')->default(10000)->after('total_harga');
            $table->integer('diskon')->default(0)->after('ongkir');
            $table->string('metode_pembayaran')->nullable()->after('diskon');
            $table->string('status_pembayaran')->default('belum_bayar')->after('metode_pembayaran');
            $table->dateTime('estimasi_selesai')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'kode_order',
                'service_id',
                'catatan',
                'alamat',
                'ongkir',
                'diskon',
                'metode_pembayaran',
                'status_pembayaran',
                'estimasi_selesai'
            ]);
        });
    }
};