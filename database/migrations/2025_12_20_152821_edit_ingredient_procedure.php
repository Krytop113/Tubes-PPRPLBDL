<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared("
            DROP PROCEDURE IF EXISTS edit_ingredient_procedure;

            CREATE PROCEDURE edit_ingredient_procedure(
                IN p_ingredient_id BIGINT,
                IN p_name VARCHAR(255),
                IN p_unit VARCHAR(255),
                IN p_price_per_unit DECIMAL(10,2),
                IN p_description TEXT,
                IN p_quantity INT,
                IN p_image_url VARCHAR(255),
                IN p_min_stock BIGINT,
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

                IF NOT EXISTS (
                    SELECT 1 FROM ingredients WHERE id = p_ingredient_id
                ) THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Gagal Update: Bahan dengan ID tersebut tidak ditemukan.';
                ELSEIF p_category_id IS NOT NULL
                    AND NOT EXISTS (
                        SELECT 1 FROM ingredient_categories WHERE id = p_category_id
                    ) THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Gagal Update: ID kategori bahan tidak valid.';
                END IF;

                UPDATE ingredients
                SET
                    name = IFNULL(p_name, name),
                    unit = IFNULL(p_unit, unit),
                    price_per_unit = IFNULL(p_price_per_unit, price_per_unit),
                    description = IFNULL(p_description, description),
                    stock_quantity = IFNULL(p_quantity, stock_quantity),
                    image_url = IFNULL(p_image_url, image_url),
                    minimum_stock_level = IFNULL(p_min_stock, minimum_stock_level),
                    ingredient_category_id = IFNULL(p_category_id, ingredient_category_id),
                    updated_at = NOW()
                WHERE id = p_ingredient_id;

                IF v_is_error = 0 THEN
                    SELECT CONCAT(
                        'Bahan dengan ID ',
                        p_ingredient_id,
                        ' berhasil diperbarui'
                    ) AS ResultMessage;
                ELSE
                    SELECT CONCAT(
                        'TRANSAKSI UPDATE GAGAL: ',
                        v_error_message
                    ) AS ErrorDetail;
                END IF;
            END
        ");
    }

    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS edit_ingredient_procedure");
    }
};