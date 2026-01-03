<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement("DROP VIEW IF EXISTS vw_user_coupons_detailed");

        DB::statement("
                CREATE VIEW vw_user_coupons_detailed AS
                SELECT 
                    cu.id AS coupon_user_id,
                    cu.user_id,
                    cu.status AS usage_status,
                    c.id AS coupon_id,
                    c.title,
                    c.description,
                    c.discount_percentage,
                    c.start_date,
                    c.end_date
                FROM coupon_users cu
                JOIN coupons c ON cu.coupon_id = c.id
            ");
    }

    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS vw_user_coupons_detailed");
    }
};
