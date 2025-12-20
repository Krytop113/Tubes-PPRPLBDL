<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coupons = [
            [
                'id' => 1,
                'title' => 'MAKANENAK',
                'description' => 'Diskon pembukaan toko untuk semua pengguna',
                'discount_percentage' => 10.00,
                'start_date' => '2023-12-01 00:00:00',
                'end_date' => '2024-12-31 23:59:59',
            ],
            [
                'id' => 2,
                'title' => 'KRIUK50',
                'description' => 'Potongan harga khusus member baru',
                'discount_percentage' => 50.00,
                'start_date' => '2024-01-01 00:00:00',
                'end_date' => '2024-06-30 23:59:59',
            ],
        ];

        foreach ($coupons as $coupon) {
            DB::table('coupons')->updateOrInsert(
                ['id' => $coupon['id']],
                array_merge($coupon, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}
