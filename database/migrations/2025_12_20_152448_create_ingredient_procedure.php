<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared("
            DROP PROCEDURE IF EXISTS create_ingredient_procedure;

            CREATE PROCEDURE create_ingredient_procedure(
                IN p_name VARCHAR(255),
                IN p_unit VARCHAR(255),
                IN p_price_per_unit DECIMAL(10,2),
                IN p_description TEXT,
                IN p_quantity INT,
                IN p_image_url VARCHAR(255),
                IN p_min_stock INT,
                IN p_category_id BIGINT
            )
            BEGIN
                DECLARE v_error_message VARCHAR(255);
                DECLARE v_is_error TINYINT(1) DEFAULT 0;

                DECLARE CONTINUE HANDLER FOR SQLEXCEPTION
                BEGIN
                    GET DIAGNOSTICS CONDITION 1 v_error_message = MESSAGE_TEXT;
                    SET v_is_error = 1;
                END;

                IF p_name IS NULL OR TRIM(p_name) = '' THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Gagal Insert: Nama bahan tidak boleh kosong.';
                ELSEIF p_unit IS NULL OR TRIM(p_unit) = '' THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Gagal Insert: Satuan unit tidak boleh kosong.';
                ELSEIF p_price_per_unit IS NULL OR p_price_per_unit <= 0 THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Gagal Insert: Harga per unit harus lebih dari nol.';
                ELSEIF p_quantity IS NULL OR p_quantity < 0 THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Gagal Insert: Kuantitas harus diisi.';
                ELSEIF p_min_stock IS NULL OR p_min_stock < 0 THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Gagal Insert: Stok minimum harus diisi.';
                ELSEIF p_category_id IS NULL OR p_category_id <= 0 THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Gagal Insert: ID kategori tidak valid.';
                ELSEIF NOT EXISTS (
                    SELECT 1 FROM ingredient_categories WHERE id = p_category_id
                ) THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Gagal Insert: ID kategori tidak ditemukan.';
                END IF;

                INSERT INTO ingredients (
                    name, unit, price_per_unit,
                    description, stock_quantity,
                    image_url, minimum_stock_level, ingredient_category_id, created_at, updated_at
                ) VALUES (
                    p_name, p_unit, p_price_per_unit,
                    p_description, p_quantity,
                    p_image_url, p_min_stock, p_category_id, NOW(), NOW()
                );

                IF v_is_error = 0 THEN
                    SELECT CONCAT(
                        'Bahan \"', p_name,
                        '\" berhasil ditambahkan dengan ID ',
                        LAST_INSERT_ID(), '.'
                    ) AS ResultMessage;
                ELSE
                    SELECT CONCAT(
                        'TRANSAKSI INSERT GAGAL: ',
                        v_error_message
                    ) AS ErrorDetail;
                END IF;
            END
        ");
    }

    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS create_ingredient_procedure");
    }
};
