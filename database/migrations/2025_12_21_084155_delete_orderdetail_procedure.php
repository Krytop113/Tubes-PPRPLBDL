<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared("
            DROP PROCEDURE IF EXISTS delete_orderdetail_procedure;

            CREATE PROCEDURE delete_orderdetail_procedure(
                IN p_id BIGINT
            )
            BEGIN
                DECLARE v_error_message VARCHAR(255);
                DECLARE v_is_error BOOLEAN DEFAULT FALSE;

                DECLARE CONTINUE HANDLER FOR SQLEXCEPTION
                BEGIN
                    GET DIAGNOSTICS CONDITION 1 v_error_message = MESSAGE_TEXT;
                    SET v_is_error = TRUE;
                END;

                IF p_id IS NULL OR p_id <= 0 THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Gagal Hapus: ID Order Detail tidak valid.';

                ELSEIF NOT EXISTS (
                    SELECT 1 FROM order_details WHERE id = p_id
                ) THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Gagal Hapus: Order Detail tidak ditemukan.';

                ELSE
                    DELETE FROM order_details
                    WHERE id = p_id;

                    IF v_is_error = FALSE THEN
                        SELECT CONCAT(
                            'Order Detail ID ',
                            p_id,
                            ' berhasil dihapus'
                        ) AS ResultMessage;
                    END IF;
                END IF;

                IF v_is_error = TRUE THEN
                    SELECT CONCAT(
                        'TRANSAKSI HAPUS GAGAL KARENA ERROR SQL: ',
                        v_error_message
                    ) AS ErrorDetail;
                END IF;
            END;
        ");
    }

    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS delete_orderdetail_procedure;');
    }
};