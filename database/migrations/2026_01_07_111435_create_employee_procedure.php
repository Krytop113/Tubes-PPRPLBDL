<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared("
            DROP PROCEDURE IF EXISTS create_employee_procedure;

            CREATE PROCEDURE create_employee_procedure(
                IN p_name VARCHAR(255),
                IN p_email VARCHAR(255),
                IN p_phone_number VARCHAR(20),
                IN p_password VARCHAR(255),
                IN p_date_of_birth DATE
            )
            BEGIN
                DECLARE v_error_message VARCHAR(255);
                DECLARE v_is_error BOOLEAN DEFAULT FALSE;
                DECLARE v_new_id INT;

                DECLARE CONTINUE HANDLER FOR SQLEXCEPTION
                BEGIN
                    GET DIAGNOSTICS CONDITION 1 v_error_message = MESSAGE_TEXT;
                    SET v_is_error = TRUE;
                END;

                IF p_name IS NULL OR p_name = '' THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Gagal Simpan: Nama tidak boleh kosong.';

                ELSEIF p_email IS NULL OR p_email = '' THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Gagal Simpan: Email tidak boleh kosong.';

                ELSEIF EXISTS (SELECT 1 FROM users WHERE email = p_email) THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Gagal Simpan: Email ini sudah terdaftar di sistem.';

                ELSE
                    INSERT INTO users (
                        name, 
                        email, 
                        phone_number,
                        date_of_birth, 
                        password, 
                        role_id, 
                        created_at, 
                        updated_at
                    ) VALUES (
                        p_name, 
                        p_email, 
                        p_phone_number,
                        p_date_of_birth, 
                        p_password, 
                        2, 
                        NOW(), 
                        NOW()
                    );

                IF v_is_error = TRUE THEN
                    SELECT CONCAT(
                        'PROSES GAGAL KARENA ERROR DATABASE: ',
                        v_error_message
                    ) AS ErrorDetail;
                END IF;

                    IF v_is_error = FALSE THEN
                        SET v_new_id = LAST_INSERT_ID();
                        SELECT CONCAT(
                            'Success: Karyawan berhasil dibuat dengan ID ',
                            v_new_id
                        ) AS ResultMessage;
                    END IF;
                END IF;
            END;
        ");
    }

    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS create_employee_procedure;");
    }
};
