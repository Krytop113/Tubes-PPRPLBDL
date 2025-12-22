<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared("
            DROP PROCEDURE IF EXISTS delete_recipe_procedure;

            CREATE PROCEDURE delete_recipe_procedure(
                IN p_recipe_id BIGINT
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
                    SELECT 1 FROM recipes WHERE id = p_recipe_id
                ) THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Gagal Hapus: Resep dengan ID tersebut tidak ditemukan.';
                END IF;

                DELETE FROM recipes
                WHERE id = p_recipe_id;

                IF v_is_error = 0 THEN
                    SELECT CONCAT(
                        'Resep dengan ID ',
                        p_recipe_id,
                        'Berhasil dihapus'
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
        DB::unprepared('DROP PROCEDURE IF EXISTS delete_recipe_procedure');
    }
};