<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared("
            DROP PROCEDURE IF EXISTS create_coupon_procedure;

            CREATE PROCEDURE create_coupon_procedure(
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

                IF p_title IS NULL OR TRIM(p_title) = '' THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Gagal Insert: Judul kupon tidak boleh kosong.';

                ELSEIF p_discount_percentage IS NULL
                       OR p_discount_percentage <= 0
                       OR p_discount_percentage > 100 THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Gagal Insert: Persentase diskon harus antara 1 - 100.';

                ELSEIF p_start_date IS NULL OR p_end_date IS NULL THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Gagal Insert: Tanggal mulai dan berakhir harus diisi.';

                ELSEIF p_end_date <= p_start_date THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Gagal Insert: Tanggal akhir harus lebih besar dari tanggal mulai.';

                ELSE
                    INSERT INTO coupons (
                        title,
                        description,
                        discount_percentage,
                        start_date,
                        end_date,
                        created_at,
                        updated_at
                    )
                    VALUES (
                        p_title,
                        p_description,
                        p_discount_percentage,
                        p_start_date,
                        p_end_date,
                        NOW(),
                        NOW()
                    );

                    IF v_is_error = FALSE THEN
                        SELECT CONCAT(
                            'Coupon \"', p_title,
                            '\" berhasil ditambahkan'
                        ) AS ResultMessage;
                    END IF;
                END IF;

                IF v_is_error = TRUE THEN
                    SELECT CONCAT(
                        'TRANSAKSI INSERT GAGAL KARENA ERROR SQL: ',
                        v_error_message
                    ) AS ErrorDetail;
                END IF;
            END;
        ");
    }

    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS create_coupon_procedure;");
    }
};