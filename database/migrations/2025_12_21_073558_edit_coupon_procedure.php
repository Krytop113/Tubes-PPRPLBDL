<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared("
            DROP PROCEDURE IF EXISTS edit_coupon_procedure;

            CREATE PROCEDURE edit_coupon_procedure(
                IN p_id BIGINT,
                IN p_title VARCHAR(255),
                IN p_description TEXT,
                IN p_discount_percentage FLOAT,
                IN p_start_date DATETIME,
                IN p_end_date DATETIME
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
                    SET MESSAGE_TEXT = 'Gagal Update: ID coupon tidak valid.';

                ELSEIF NOT EXISTS (
                    SELECT 1 FROM coupons WHERE id = p_id
                ) THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Gagal Update: Coupon dengan ID tersebut tidak ditemukan.';

                ELSEIF p_discount_percentage IS NOT NULL
                       AND (p_discount_percentage <= 0 OR p_discount_percentage > 100) THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Gagal Update: Persentase diskon harus antara 1 - 100.';

                ELSEIF p_start_date IS NOT NULL
                       AND p_end_date IS NOT NULL
                       AND p_end_date <= p_start_date THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Gagal Update: Tanggal akhir harus lebih besar dari tanggal mulai.';

                ELSE
                    UPDATE coupons
                    SET
                        title = IFNULL(p_title, title),
                        description = IFNULL(p_description, description),
                        discount_percentage = IFNULL(p_discount_percentage, discount_percentage),
                        start_date = IFNULL(p_start_date, start_date),
                        end_date = IFNULL(p_end_date, end_date),
                        updated_at = NOW()
                    WHERE id = p_id;

                    IF v_is_error = FALSE THEN
                        SELECT CONCAT(
                            'Coupon dengan ID ',
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
        DB::unprepared("DROP PROCEDURE IF EXISTS edit_coupon_procedure;");
    }
};