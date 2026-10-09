<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('gudangs')->insert([
            ['nama_gudang' => 'Gudang Pusat', 'alamat' => 'Bandung'],
            ['nama_gudang' => 'Gudang Cabang', 'alamat' => 'Jakarta'],
        ]);

        DB::table('barangs')->insert([
            ['sku' => 'BRG-001', 'nama_barang' => 'Kopi Bubuk 200g', 'kategori' => 'Minuman', 'satuan' => 'pcs', 'harga_pokok' => 15000, 'harga_jual' => 20000, 'status_aktif' => true],
            ['sku' => 'BRG-002', 'nama_barang' => 'Gula Pasir 1kg', 'kategori' => 'Sembako', 'satuan' => 'kg', 'harga_pokok' => 14000, 'harga_jual' => 17000, 'status_aktif' => true],
            ['sku' => 'BRG-003', 'nama_barang' => 'Teh Celup', 'kategori' => 'Minuman', 'satuan' => 'box', 'harga_pokok' => 5000, 'harga_jual' => 7000, 'status_aktif' => true],
        ]);

        DB::table('pelanggans')->insert([
            ['nama_pelanggan' => 'Toko Maju', 'no_hp' => '08123456789', 'alamat' => 'Bandung'],
            ['nama_pelanggan' => 'Budi', 'no_hp' => null, 'alamat' => 'Cimahi'],
        ]);
    }
}
