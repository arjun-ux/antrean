<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $poli = [
            [
                'nama_poli' => 'BP Umum',
                'id_poli_bpjs' => '001',
            ],
            [
                'nama_poli' => 'BP Gigi',
                'id_poli_bpjs' => '002',
            ],
            [
                'nama_poli' => 'BP Mata',
                'id_poli_bpjs' => '010',
            ],
            [
                'nama_poli' => 'Poli Spesialis',
                'id_poli_bpjs' => '',
            ],
            [
                'nama_poli' => 'KIA',
                'id_poli_bpjs' => '003',
            ],
            [
                'nama_poli' => 'Laborat',
                'id_poli_bpjs' => '004',
            ],
            [
                'nama_poli' => 'UGD',
                'id_poli_bpjs' => '005',
            ],
            [
                'nama_poli' => 'Fisioterapi',
                'id_poli_bpjs' => '006',
            ],
            [
                'nama_poli' => 'Gizi',
                'id_poli_bpjs' => '',
            ],
            [
                'nama_poli' => 'Kesling',
                'id_poli_bpjs' => '',
            ],
            [
                'nama_poli' => 'MTBS',
                'id_poli_bpjs' => '003',
            ],
            [
                'nama_poli' => 'Imunisasi',
                'id_poli_bpjs' => '023',
            ],
            [
                'nama_poli' => 'TB',
                'id_poli_bpjs' => '',
            ],
            [
                'nama_poli' => 'KB',
                'id_poli_bpjs' => '008',
            ],
            [
                'nama_poli' => 'Lansia',
                'id_poli_bpjs' => '001',
            ],
            [
                'nama_poli' => 'KRR',
                'id_poli_bpjs' => '',
            ],
            [
                'nama_poli' => 'IVA',
                'id_poli_bpjs' => '',
            ],
            [
                'nama_poli' => 'VK',
                'id_poli_bpjs' => '',
            ],
            [
                'nama_poli' => 'LANSIA',
                'id_poli_bpjs' => '012',
            ],
            [
                'nama_poli' => 'Poli TB & Paru',
                'id_poli_bpjs' => '033',
            ],
            [
                'nama_poli' => 'Poli Persalinan',
                'id_poli_bpjs' => '011',
            ],
        ];

        DB::table('polis')->insert($poli);

    }
}
