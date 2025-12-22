<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared("
            DROP PROCEDURE IF EXISTS delete_employee_procedure;

            CREATE PROCEDURE delete_employee_procedure(
                IN p_employee_id BIGINT
            )
            BEGIN
                DECLARE v_error_message VARCHAR(255);
                DECLARE v_is_error TINYINT(1) DEFAULT 0;

                DECLARE CONTINUE HANDLER FOR SQLEXCEPTION
                BEGIN
                    GET DIAGNOSTICS CONDITION 1 v_error_message = MESSAGE_TEXT;
                    SET v_is_error = 1;
                END;

                IF NOT EXISTS (
                    SELECT 1 FROM users WHERE id = p_employee_id
                ) THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Gagal Hapus: Karyawan dengan ID tersebut tidak ditemukan.';
                END IF;

                DELETE FROM users
                WHERE id = p_employee_id;

                IF v_is_error = 0 THEN
                    SELECT CONCAT(
                        'Karyawan dengan ID ',
                        p_employee_id,
                        ' berhasil dihapus'
                    ) AS ResultMessage;
                ELSE
                    SELECT CONCAT(
                        'TRANSAKSI HAPUS GAGAL KARENA ERROR SQL: ',
                        v_error_message
                    ) AS ErrorDetail;
                END IF;
            END
        ");
    }

    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS delete_employee_procedure');
    }
};
