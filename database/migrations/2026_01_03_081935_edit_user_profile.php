<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS edit_user_profile");

        DB::unprepared("
            CREATE PROCEDURE edit_user_profile(
                IN p_user_id INT,
                IN p_name VARCHAR(50),
                IN p_email VARCHAR(255),
                IN p_phone_number VARCHAR(20),
                IN p_date_of_birth DATE
            )
            BEGIN
                UPDATE users 
                SET 
                    name = p_name,
                    email = p_email,
                    phone_number = p_phone_number,
                    date_of_birth = p_date_of_birth,
                    updated_at = NOW()
                WHERE id = p_user_id;
            END
        ");
    }

    public function down()
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS edit_user_profile");
    }
};
