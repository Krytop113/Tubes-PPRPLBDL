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
                IN p_status VARCHAR(255),
                IN p_total_raw DECIMAL(10,2),
                IN p_user_id BIGINT
            )
            BEGIN
                DECLARE v_error_message VARCHAR(255);
                DECLARE v_is_error BOOLEAN DEFAULT FALSE;

                DECLARE CONTINUE HANDLER FOR SQLEXCEPTION
                BEGIN
                    GET DIAGNOSTICS CONDITION 1 v_error_message = MESSAGE_TEXT;
                    SET v_is_error = TRUE;
                END;

                IF p_status IS NULL OR TRIM(p_status) = '' THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Gagal Insert: Status order tidak boleh kosong.';

                ELSEIF p_total_raw IS NULL OR p_total_raw <= 0 THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Gagal Insert: Total order harus lebih dari nol.';

                ELSEIF p_user_id IS NULL OR p_user_id <= 0 THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Gagal Insert: ID user tidak valid.';

                ELSEIF NOT EXISTS (
                    SELECT 1 FROM users WHERE id = p_user_id
                ) THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Gagal Insert: User tidak ditemukan.';

                ELSE
                    INSERT INTO orders (
                        status,
                        total_raw,
                        user_id,
                        created_at,
                        updated_at
                    )
                    VALUES (
                        p_status,
                        p_total_raw,
                        p_user_id,
                        NOW(),
                        NOW()
                    );

                    IF v_is_error = FALSE THEN
                        SELECT CONCAT(
                            'Order berhasil dibuat dengan ID ',
                            LAST_INSERT_ID()
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
        DB::unprepared('DROP PROCEDURE IF EXISTS create_orders_procedure;');
    }
};