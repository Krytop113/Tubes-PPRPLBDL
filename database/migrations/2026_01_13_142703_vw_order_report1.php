<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;


return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared("
            CREATE OR REPLACE VIEW vw_order_report1 AS
            SELECT 
                o.id AS order_id,
                o.status AS order_status,
                o.total_raw AS subtotal,
                o.created_at AS tanggal_order,
                u.id AS user_id,
                u.name AS nama_pelanggan,
                u.email AS email_pelanggan,
                u.phone_number AS no_hp,
                p.method AS metode_pembayaran,
                p.total_amount AS total_bayar,
                p.coupon_amount AS diskon_kupon,
                p.date AS tanggal_pembayaran,
                (SELECT COUNT(*) FROM order_details od WHERE od.order_id = o.id) AS total_jenis_barang,
                (SELECT SUM(quantity) FROM order_details od WHERE od.order_id = o.id) AS total_item_terjual
            FROM 
                orders o
            JOIN 
                users u ON o.user_id = u.id
            LEFT JOIN 
                payments p ON o.id = p.order_id
            WHERE 
                o.status IN ('paid', 'done')
        ");
    }

    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS vw_order_report1");
    }
};
