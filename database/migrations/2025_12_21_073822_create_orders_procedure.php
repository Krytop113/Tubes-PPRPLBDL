<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared("
            DROP PROCEDURE IF EXISTS create_orders_procedure;

            CREATE PROCEDURE create_orders_procedure(
                IN p_status VARCHAR(50),
                IN p_total_raw DECIMAL(10,2),
                IN p_user_id BIGINT
            )
            BEGIN
                DECLARE v_new_id VARCHAR(20);
                DECLARE v_today_prefix VARCHAR(8);
                DECLARE v_last_increment INT;
                DECLARE v_error_message VARCHAR(255);
                DECLARE v_is_error BOOLEAN DEFAULT FALSE;

                DECLARE CONTINUE HANDLER FOR SQLEXCEPTION
                BEGIN
                    GET DIAGNOSTICS CONDITION 1 v_error_message = MESSAGE_TEXT;
                    SET v_is_error = TRUE;
                END;

                SET v_today_prefix = DATE_FORMAT(NOW(), '%Y%m%d');

                SELECT IFNULL(MAX(CAST(SUBSTRING(id, 10) AS UNSIGNED)), 0) INTO v_last_increment
                FROM orders
                WHERE id LIKE CONCAT(v_today_prefix, '-%');

                SET v_new_id = CONCAT(v_today_prefix, '-', LPAD(v_last_increment + 1, 4, '0'));

                IF p_status IS NULL OR TRIM(p_status) = '' THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Gagal Insert: Status order tidak boleh kosong.';
                
                ELSEIF p_user_id IS NULL OR p_user_id <= 0 THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Gagal Insert: ID user tidak valid.';

                ELSEIF NOT EXISTS (SELECT 1 FROM users WHERE id = p_user_id) THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Gagal Insert: User tidak ditemukan.';

                ELSEIF p_total_raw IS NULL OR p_total_raw < 0 THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Gagal Insert: Total order tidak valid.';

                ELSE
                    INSERT INTO orders (
                        id,
                        status,
                        total_raw,
                        user_id,
                        created_at,
                        updated_at
                    )
                    VALUES (
                        v_new_id,
                        p_status,
                        p_total_raw,
                        p_user_id,
                        NOW(),
                        NOW()
                    );

                    IF v_is_error = FALSE THEN
                        SELECT v_new_id AS generated_id, 'Order berhasil dibuat' AS ResultMessage;
                    END IF;
                END IF;

                IF v_is_error = TRUE THEN
                    SELECT CONCAT('TRANSAKSI GAGAL KARENA ERROR SQL: ', v_error_message) AS ErrorDetail;
                END IF;
            END;
        ");
    }

    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS create_orders_procedure;');
    }
};
