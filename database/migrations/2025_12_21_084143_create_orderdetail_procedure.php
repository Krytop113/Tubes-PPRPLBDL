<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared("
            DROP PROCEDURE IF EXISTS create_orderdetail_procedure;

            CREATE PROCEDURE create_orderdetail_procedure(
                IN p_order_id BIGINT,
                IN p_ingredient_id BIGINT,
                IN p_quantity INT,
                IN p_price DECIMAL(10,2)
            )
            BEGIN
                DECLARE v_error_message VARCHAR(255);
                DECLARE v_is_error BOOLEAN DEFAULT FALSE;
                DECLARE v_order_status VARCHAR(50);

                DECLARE CONTINUE HANDLER FOR SQLEXCEPTION
                BEGIN
                    GET DIAGNOSTICS CONDITION 1 v_error_message = MESSAGE_TEXT;
                    SET v_is_error = TRUE;
                END;

                IF p_quantity IS NULL OR p_quantity <= 0 THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Gagal Insert: Quantity tidak valid.';

                ELSEIF p_price IS NULL OR p_price <= 0 THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Gagal Insert: Harga tidak valid.';

                ELSEIF NOT EXISTS (
                    SELECT 1 FROM ingredients WHERE id = p_ingredient_id
                ) THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Gagal Insert: Ingredient tidak ditemukan.';

                ELSEIF NOT EXISTS (
                    SELECT 1 FROM orders WHERE id = p_order_id
                ) THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Gagal Insert: Order tidak ditemukan.';

                ELSE
                    SELECT status
                    INTO v_order_status
                    FROM orders
                    WHERE id = p_order_id;

                    IF v_order_status <> 'cart' THEN
                        SIGNAL SQLSTATE '45000'
                        SET MESSAGE_TEXT = 'Gagal Insert: Order bukan cart.';
                    END IF;

                    IF EXISTS (
                        SELECT 1
                        FROM order_details
                        WHERE order_id = p_order_id
                          AND ingredient_id = p_ingredient_id
                          AND status = 'cart'
                    ) THEN
                        SIGNAL SQLSTATE '45000'
                        SET MESSAGE_TEXT = 'Ingredient sudah ada di cart.';
                    END IF;

                    INSERT INTO order_details (
                        order_id,
                        ingredient_id,
                        quantity,
                        price,
                        status,
                        created_at,
                        updated_at
                    )
                    VALUES (
                        p_order_id,
                        p_ingredient_id,
                        p_quantity,
                        p_price,
                        'cart',
                        NOW(),
                        NOW()
                    );

                    IF v_is_error = FALSE THEN
                        SELECT CONCAT(
                            'Ingredient berhasil ditambahkan ke cart untuk Order ID ',
                            p_order_id
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
        DB::unprepared('DROP PROCEDURE IF EXISTS create_orderdetail_procedure;');
    }
};
