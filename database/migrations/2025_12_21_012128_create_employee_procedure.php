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
                IN p_nama VARCHAR(255),
                IN p_email VARCHAR(255),
                IN p_password VARCHAR(255)
            )
            BEGIN
                DECLARE v_error_message VARCHAR(255);
                DECLARE v_is_error TINYINT(1) DEFAULT 0;

                DECLARE CONTINUE HANDLER FOR SQLEXCEPTION
                BEGIN
                    GET DIAGNOSTICS CONDITION 1 v_error_message = MESSAGE_TEXT;
                    SET v_is_error = 1;
                END;

                IF p_nama IS NULL OR TRIM(p_nama) = '' THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Gagal Insert: Nama karyawan tidak boleh kosong.';
                ELSEIF p_password IS NULL OR TRIM(p_password) = '' THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Gagal Insert: Password tidak boleh kosong.';
                ELSEIF p_email IS NULL OR TRIM(p_email) = '' THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Gagal Insert: Email tidak boleh kosong.';
                ELSEIF p_role_id IS NULL OR p_role_id <= 0 THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Gagal Insert: ID Role tidak valid.';
                ELSEIF NOT EXISTS (
                    SELECT 1 FROM roles WHERE id = p_role_id
                ) THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Gagal Insert: ID Role tidak ditemukan.';
                END IF;

                INSERT INTO users (
                    name, email, password,
                    role_id, created_at, updated_at
                ) VALUES (
                    p_nama, p_email, p_password,
                    2, NOW(), NOW()
                );

                IF v_is_error = 0 THEN
                    SELECT CONCAT(
                        'User \"', p_nama,
                        '\" berhasil ditambahkan'
                    ) AS ResultMessage;
                ELSE
                    SELECT CONCAT(
                        'TRANSAKSI GAGAL: ',
                        v_error_message
                    ) AS ErrorDetail;
                END IF;
            END
        ");
    }

    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS create_employee_procedure');
    }
};