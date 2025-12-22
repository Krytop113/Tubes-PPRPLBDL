<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared("
            DROP PROCEDURE IF EXISTS create_couponuser_procedure;

            CREATE PROCEDURE create_couponuser_procedure(
                IN p_user_id BIGINT,
                IN p_coupon_id BIGINT
            )
            BEGIN
                DECLARE v_error_message VARCHAR(255);
                DECLARE v_is_error BOOLEAN DEFAULT FALSE;

                DECLARE CONTINUE HANDLER FOR SQLEXCEPTION
                BEGIN
                    GET DIAGNOSTICS CONDITION 1 v_error_message = MESSAGE_TEXT;
                    SET v_is_error = TRUE;
                END;

                IF p_user_id IS NULL OR p_user_id <= 0 THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Gagal Insert: ID User tidak valid.';

                ELSEIF p_coupon_id IS NULL OR p_coupon_id <= 0 THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Gagal Insert: ID Coupon tidak valid.';

                ELSEIF NOT EXISTS (
                    SELECT 1 FROM users WHERE id = p_user_id
                ) THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Gagal Insert: User tidak ditemukan.';

                ELSEIF NOT EXISTS (
                    SELECT 1 FROM coupons WHERE id = p_coupon_id
                ) THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Gagal Insert: Coupon tidak ditemukan.';

                ELSEIF EXISTS (
                    SELECT 1 FROM coupon_users
                    WHERE user_id = p_user_id
                    AND coupon_id = p_coupon_id
                ) THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Gagal Insert: Coupon sudah dimiliki oleh user ini.';

                ELSE
                    INSERT INTO coupon_users (
                        user_id,
                        coupon_id,
                        status,
                        created_at,
                        updated_at
                    )
                    VALUES (
                        p_user_id,
                        p_coupon_id,
                        'active',
                        NOW(),
                        NOW()
                    );

                    IF v_is_error = FALSE THEN
                        SELECT CONCAT(
                            'Coupon ID ',
                            p_coupon_id,
                            ' berhasil diberikan ke User ID ',
                            p_user_id
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
        DB::unprepared('DROP PROCEDURE IF EXISTS create_couponuser_procedure;');
    }
};