<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared("
            DROP PROCEDURE IF EXISTS update_couponuser_procedure;

            CREATE PROCEDURE update_couponuser_procedure(
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
                    SET MESSAGE_TEXT = 'Gagal Update: ID User tidak valid.';

                ELSEIF p_coupon_id IS NULL OR p_coupon_id <= 0 THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Gagal Update: ID Coupon tidak valid.';

                ELSEIF NOT EXISTS (
                    SELECT 1 FROM coupon_users
                    WHERE user_id = p_user_id
                    AND coupon_id = p_coupon_id
                ) THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Gagal Update: Coupon tidak dimiliki oleh user ini.';

                ELSEIF EXISTS (
                    SELECT 1 FROM coupon_users
                    WHERE user_id = p_user_id
                    AND coupon_id = p_coupon_id
                    AND status = 'used'
                ) THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Gagal Update: Coupon sudah digunakan.';

                ELSE
                    UPDATE coupon_users
                    SET
                        status = 'used',
                        updated_at = NOW()
                    WHERE
                        user_id = p_user_id
                        AND coupon_id = p_coupon_id;

                    IF v_is_error = FALSE THEN
                        SELECT CONCAT(
                            'Coupon ID ',
                            p_coupon_id,
                            ' berhasil diubah menjadi USED untuk User ID ',
                            p_user_id
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
        DB::unprepared('DROP PROCEDURE IF EXISTS update_couponuser_procedure;');
    }
};
