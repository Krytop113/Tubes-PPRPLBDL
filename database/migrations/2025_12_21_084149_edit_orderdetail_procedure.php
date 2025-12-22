<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared("
            DROP PROCEDURE IF EXISTS edit_orderdetail_procedure;

            CREATE PROCEDURE edit_orderdetail_procedure(
                IN p_id BIGINT,
                IN p_price DECIMAL(10,2),
                IN p_status VARCHAR(255),
                IN p_ingredient_id BIGINT,
                IN p_order_id BIGINT
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
                    SET MESSAGE_TEXT = 'Gagal Update: ID Order Detail tidak valid.';

                ELSEIF NOT EXISTS (
                    SELECT 1 FROM order_details WHERE id = p_id
                ) THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Gagal Update: Order Detail tidak ditemukan.';

                ELSEIF p_ingredient_id IS NOT NULL AND NOT EXISTS (
                    SELECT 1 FROM ingredients WHERE id = p_ingredient_id
                ) THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Gagal Update: Ingredient tidak ditemukan.';

                ELSEIF p_order_id IS NOT NULL AND NOT EXISTS (
                    SELECT 1 FROM orders WHERE id = p_order_id
                ) THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Gagal Update: Order tidak ditemukan.';

                ELSE
                    UPDATE order_details
                    SET
                        price = IFNULL(p_price, price),
                        status = IFNULL(p_status, status),
                        ingredient_id = IFNULL(p_ingredient_id, ingredient_id),
                        order_id = IFNULL(p_order_id, order_id),
                        updated_at = NOW()
                    WHERE id = p_id;

                    IF v_is_error = FALSE THEN
                        SELECT CONCAT(
                            'Order Detail ID ',
                            p_id,
                            ' berhasil diperbarui'
                        ) AS ResultMessage;
                    END IF;
                END IF;

                IF v_is_error = TRUE THEN
                    SELECT CONCAT(
                        'TRANSAKSI UPDATE GAGAL KARENA ERROR SQL: ',
                        v_error_message
                    ) AS ErrorDetail;
                END IF;
            END;
        ");
    }

    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS edit_orderdetail_procedure;');
    }
};