<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared("
            DROP PROCEDURE IF EXISTS create_recipe_ingredient_procedure;

            CREATE PROCEDURE create_recipe_ingredient_procedure(
                IN p_quantity_required INT,
                IN p_unit VARCHAR(50),
                IN p_recipe_id INT,
                IN p_ingredient_id INT
            )
            BEGIN
                DECLARE v_error_message VARCHAR(255);
                DECLARE v_is_error BOOLEAN DEFAULT FALSE;
                DECLARE v_new_id INT;

                DECLARE CONTINUE HANDLER FOR SQLEXCEPTION
                BEGIN
                    GET DIAGNOSTICS CONDITION 1 v_error_message = MESSAGE_TEXT;
                    SET v_is_error = TRUE;
                END;

                IF p_quantity_required IS NULL OR p_quantity_required <= 0 THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Gagal Simpan: Jumlah (quantity) harus lebih dari 0.';

                ELSEIF NOT EXISTS (SELECT 1 FROM recipes WHERE id = p_recipe_id) THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Gagal Simpan: ID Resep tidak ditemukan.';

                ELSEIF NOT EXISTS (SELECT 1 FROM ingredients WHERE id = p_ingredient_id) THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Gagal Simpan: ID Bahan (Ingredient) tidak ditemukan.';

                ELSE
                    INSERT INTO recipe_ingredients (
                        quantity_required, 
                        unit, 
                        recipe_id, 
                        ingredient_id, 
                        created_at, 
                        updated_at
                    ) VALUES (
                        p_quantity_required, 
                        p_unit, 
                        p_recipe_id, 
                        p_ingredient_id, 
                        NOW(), 
                        NOW()
                    );

                    IF v_is_error = TRUE THEN
                        SELECT CONCAT('PROSES GAGAL KARENA ERROR DATABASE: ', v_error_message) AS ResultMessage;
                    ELSE
                        SET v_new_id = LAST_INSERT_ID();
                        SELECT CONCAT('Success: Bahan berhasil ditambahkan ke resep. ID: ', v_new_id) AS ResultMessage;
                    END IF;
                END IF;
            END;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS create_recipe_ingredient_procedure;");
    }
};
