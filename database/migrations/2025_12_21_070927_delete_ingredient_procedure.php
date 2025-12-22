<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared("
            DROP PROCEDURE IF EXISTS delete_ingredient_procedure;

            CREATE PROCEDURE delete_ingredient_procedure(
                IN p_ingredient_id BIGINT
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
                    SELECT 1 FROM ingredients WHERE id = p_ingredient_id
                ) THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Gagal Hapus: Bahan dengan ID tersebut tidak ditemukan.';
                END IF;

                DELETE FROM ingredients
                WHERE id = p_ingredient_id;

                IF v_is_error = 0 THEN
                    SELECT CONCAT(
                        'Bahan dengan ID ',
                        p_ingredient_id,
                        ' berhasil dihapus'
                    ) AS ResultMessage;
                ELSE
                    SELECT CONCAT(
                        'TRANSAKSI HAPUS GAGAL: ',
                        v_error_message
                    ) AS ErrorDetail;
                END IF;
            END
        ");
    }

    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS delete_ingredient_procedure');
    }
};