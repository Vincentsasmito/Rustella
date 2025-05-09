<?php

namespace Database\Seeders;

use App\Models\Delivery;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DeliverySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Administrative City & Subdistricts of Central Jakarta
        Delivery::create([
            'city'              => 'Jakarta Pusat',
            'subdistrict'       => 'Gambir',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Jakarta Pusat',
            'subdistrict'       => 'Sawah Besar',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Jakarta Pusat',
            'subdistrict'       => 'Kemayoran',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Jakarta Pusat',
            'subdistrict'       => 'Senen',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Jakarta Pusat',
            'subdistrict'       => 'Cempaka Putih',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Jakarta Pusat',
            'subdistrict'       => 'Johar Baru',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Jakarta Pusat',
            'subdistrict'       => 'Menteng',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Jakarta Pusat',
            'subdistrict'       => 'Tanah Abang',
            'fee'               => 10000,
        ]);

        //Administrative City & Subdistricts of North Jakarta
        Delivery::create([
            'city'              => 'Jakarta Utara',
            'subdistrict'       => 'Penjaringan',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Jakarta Utara',
            'subdistrict'       => 'Pademangan',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Jakarta Utara',
            'subdistrict'       => 'Tanjung Priok',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Jakarta Utara',
            'subdistrict'       => 'Koja',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Jakarta Utara',
            'subdistrict'       => 'Kelapa Gading',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Jakarta Utara',
            'subdistrict'       => 'Cilincing',
            'fee'               => 10000,
        ]);
        
        //Administrative City & Subdistricts of West Jakarta
        Delivery::create([
            'city'              => 'Jakarta Barat',
            'subdistrict'       => 'Cengkareng',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Jakarta Barat',
            'subdistrict'       => 'Kali Deres',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Jakarta Barat',
            'subdistrict'       => 'Grogol Petamburan',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Jakarta Barat',
            'subdistrict'       => 'Palmerah',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Jakarta Barat',
            'subdistrict'       => 'Taman Sari',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Jakarta Barat',
            'subdistrict'       => 'Tambora',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Jakarta Barat',
            'subdistrict'       => 'Kebun Jeruk',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Jakarta Barat',
            'subdistrict'       => 'Kembangan',
            'fee'               => 10000,
        ]);

        //Administrative City & Subdistricts of South Jakarta
        Delivery::create([
            'city'              => 'Jakarta Selatan',
            'subdistrict'       => 'Tebet',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Jakarta Selatan',
            'subdistrict'       => 'Setiabudi',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Jakarta Selatan',
            'subdistrict'       => 'Mampang Prapatan',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Jakarta Selatan',
            'subdistrict'       => 'Pasar Minggu',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Jakarta Selatan',
            'subdistrict'       => 'Jagaraksa',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Jakarta Selatan',
            'subdistrict'       => 'Kebayoran Baru',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Jakarta Selatan',
            'subdistrict'       => 'Cilandak',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Jakarta Selatan',
            'subdistrict'       => 'Kebayoran Lama',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Jakarta Selatan',
            'subdistrict'       => 'Pesanggrahan',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Jakarta Selatan',
            'subdistrict'       => 'Pancoran',
            'fee'               => 10000,
        ]);

         //Administrative City & Subdistricts of East Jakarta
         Delivery::create([
            'city'              => 'Jakarta Timur',
            'subdistrict'       => 'Matraman',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Jakarta Timur',
            'subdistrict'       => 'Pulo Gadung',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Jakarta Timur',
            'subdistrict'       => 'Jatinegara',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Jakarta Timur',
            'subdistrict'       => 'Duren Sawit',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Jakarta Timur',
            'subdistrict'       => 'Kramat Jati',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Jakarta Timur',
            'subdistrict'       => 'Makasar',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Jakarta Timur',
            'subdistrict'       => 'Pasar Rebo',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Jakarta Timur',
            'subdistrict'       => 'Cipayung',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Jakarta Timur',
            'subdistrict'       => 'Ciracas',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Jakarta Timur',
            'subdistrict'       => 'Cakung',
            'fee'               => 10000,
        ]);

        //Administrative City & Subdistricts of Kabupaten Tangerang
        Delivery::create([
            'city'              => 'Kabupaten Tangerang',
            'subdistrict'       => 'Balaraja',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Kabupaten Tangerang',
            'subdistrict'       => 'Cikupa',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Kabupaten Tangerang',
            'subdistrict'       => 'Cisauk',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Kabupaten Tangerang',
            'subdistrict'       => 'Cisoka',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Kabupaten Tangerang',
            'subdistrict'       => 'Curug',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Kabupaten Tangerang',
            'subdistrict'       => 'Gunung Kaler',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Kabupaten Tangerang',
            'subdistrict'       => 'Jambe',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Kabupaten Tangerang',
            'subdistrict'       => 'Jayanti',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Kabupaten Tangerang',
            'subdistrict'       => 'Kelapa Dua',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Kabupaten Tangerang',
            'subdistrict'       => 'Kemiri',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Kabupaten Tangerang',
            'subdistrict'       => 'Kresek',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Kabupaten Tangerang',
            'subdistrict'       => 'Kronjo',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Kabupaten Tangerang',
            'subdistrict'       => 'Kosambi',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Kabupaten Tangerang',
            'subdistrict'       => 'Legok',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Kabupaten Tangerang',
            'subdistrict'       => 'Mauk',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Kabupaten Tangerang',
            'subdistrict'       => 'Mekarbaru',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Kabupaten Tangerang',
            'subdistrict'       => 'Pagedangan',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Kabupaten Tangerang',
            'subdistrict'       => 'Buaran Bambu',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Kabupaten Tangerang',
            'subdistrict'       => 'Panongan',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Kabupaten Tangerang',
            'subdistrict'       => 'Pasar Kemis',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Kabupaten Tangerang',
            'subdistrict'       => 'Rajeg',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Kabupaten Tangerang',
            'subdistrict'       => 'Sepatan',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Kabupaten Tangerang',
            'subdistrict'       => 'Sepatan Timur',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Kabupaten Tangerang',
            'subdistrict'       => 'Sindang Jaya',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Kabupaten Tangerang',
            'subdistrict'       => 'Solear',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Kabupaten Tangerang',
            'subdistrict'       => 'Sukadiri',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Kabupaten Tangerang',
            'subdistrict'       => 'Sukamulya',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Kabupaten Tangerang',
            'subdistrict'       => 'Teluknaga',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Kabupaten Tangerang',
            'subdistrict'       => 'Tigaraksa',
            'fee'               => 10000,
        ]);

        //Administrative City & Subdistricts of Kota Tangerang
        Delivery::create([
            'city'              => 'Kota Tangerang',
            'subdistrict'       => 'Batuceper',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Kota Tangerang',
            'subdistrict'       => 'Benda',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Kota Tangerang',
            'subdistrict'       => 'Cibodas',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Kota Tangerang',
            'subdistrict'       => 'Ciledug',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Kota Tangerang',
            'subdistrict'       => 'Cipondoh',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Kota Tangerang',
            'subdistrict'       => 'Jatiuwung',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Kota Tangerang',
            'subdistrict'       => 'Karangtengah',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Kota Tangerang',
            'subdistrict'       => 'Karawaci',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Kota Tangerang',
            'subdistrict'       => 'Larangan',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Kota Tangerang',
            'subdistrict'       => 'Neglasari',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Kota Tangerang',
            'subdistrict'       => 'Periuk',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Kota Tangerang',
            'subdistrict'       => 'Pinang',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Kota Tangerang',
            'subdistrict'       => 'Tangerang',
            'fee'               => 10000,
        ]);

        //Administrative City & Subdistricts of Tangerang Selatan
        Delivery::create([
            'city'              => 'Tangerang Selatan',
            'subdistrict'       => 'Ciputat',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Tangerang Selatan',
            'subdistrict'       => 'Ciputat Timur',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Tangerang Selatan',
            'subdistrict'       => 'Pamulang',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Tangerang Selatan',
            'subdistrict'       => 'Pondok Aren',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Tangerang Selatan',
            'subdistrict'       => 'Serpong',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Tangerang Selatan',
            'subdistrict'       => 'Serpong Utara',
            'fee'               => 10000,
        ]);
        Delivery::create([
            'city'              => 'Tangerang Selatan',
            'subdistrict'       => 'Setu',
            'fee'               => 10000,
        ]);
        
    }
}
