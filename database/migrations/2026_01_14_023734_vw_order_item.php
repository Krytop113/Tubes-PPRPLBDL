<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        DB::unprepared("
        CREATE OR REPLACE VIEW vw_order_item AS
        SELECT 
            i.id AS ingredient_id,
            i.name AS nama_bahan,
            i.unit AS satuan,
            SUM(od.quantity) AS total_kuantitas,
            COUNT(od.id) AS total_kali_dipesan,
            SUM(od.quantity * od.price) AS total_omzet_bahan
        FROM 
            order_details od
        JOIN 
            ingredients i ON od.ingredient_id = i.id
        JOIN 
            orders o ON od.order_id = o.id
        WHERE 
            o.status IN ('paid', 'done')
        GROUP BY 
            i.id, i.name, i.unit
        ORDER BY 
            total_kuantitas DESC
    ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP VIEW IF EXISTS vw_order_item");
    }
};
