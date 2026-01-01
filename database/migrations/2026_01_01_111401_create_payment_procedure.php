<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared("
            DROP PROCEDURE IF EXISTS create_payment_procedure;

            CREATE PROCEDURE create_payment_procedure(
                IN p_coupon_amount DECIMAL(10, 2),
                IN p_shipping_cost DECIMAL(10, 2),
                IN p_total_amount DECIMAL(10, 2),
                IN p_method VARCHAR(255),
                IN p_date DATETIME,
                IN p_order_id BIGINT,
                IN p_coupon_id BIGINT
            )
            BEGIN
                DECLARE v_error_message VARCHAR(255);
                DECLARE EXIT HANDLER FOR SQLEXCEPTION
                BEGIN
                    -- Menangkap pesan error SQL
                    GET DIAGNOSTICS CONDITION 1 v_error_message = MESSAGE_TEXT;
                    ROLLBACK;
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = v_error_message;
                END;

                START TRANSACTION;

                IF NOT EXISTS (SELECT 1 FROM orders WHERE id = p_order_id AND status = 'pending') THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Gagal: Order tidak ditemukan atau sudah dibayar/dibatalkan.';
                
                ELSEIF p_coupon_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM coupon_users WHERE id = p_coupon_id) THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Gagal: Kupon tidak valid.';

                ELSE
                    INSERT INTO payments (
                        coupon_amount, 
                        shipping_cost, 
                        amount, 
                        method, 
                        date, 
                        order_id, 
                        coupon_id, 
                        created_at, 
                        updated_at
                    ) 
                    VALUES (
                        p_coupon_amount, 
                        p_shipping_cost, 
                        p_total_amount, 
                        p_method, 
                        p_date, 
                        p_order_id, 
                        p_coupon_id, 
                        NOW(), 
                        NOW()
                    );

                    UPDATE orders 
                    SET status = 'paid', 
                        updated_at = NOW() 
                    WHERE id = p_order_id;

                    IF p_coupon_id IS NOT NULL THEN
                        UPDATE coupon_users 
                        SET status = 'used', 
                            updated_at = NOW() 
                        WHERE id = p_coupon_id;
                    END IF;

                    COMMIT;

                    SELECT 'SUCCESS' AS Status, 'Pembayaran berhasil diproses.' AS Message;
                END IF;
            END;
        ");
    }

    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS create_payment_procedure;");
    }
};
