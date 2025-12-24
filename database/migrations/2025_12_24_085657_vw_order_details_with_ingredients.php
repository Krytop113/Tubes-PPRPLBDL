<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::statement("
            CREATE VIEW vw_order_details_with_ingredients AS
            SELECT
                od.id AS order_detail_id,
                od.order_id,
                od.ingredient_id,
                od.quantity,
                od.price,
                od.status AS order_detail_status,
                od.created_at,
                od.updated_at,
                i.name AS ingredient_name,
                i.image_url AS ingredient_image,
                i.unit AS ingredient_unit,
                o.user_id,
                o.status AS order_status
            FROM order_details od
            JOIN ingredients i ON i.id = od.ingredient_id
            JOIN orders o ON o.id = od.order_id
        ");
    }

    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS vw_order_details_with_ingredients');
    }
};