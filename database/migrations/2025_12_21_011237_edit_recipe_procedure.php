<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared("
            DROP PROCEDURE IF EXISTS edit_recipe_procedure;

            CREATE PROCEDURE edit_recipe_procedure(
                IN p_recipe_id BIGINT,
                IN p_title VARCHAR(255),
                IN p_description TEXT,
                IN p_steps TEXT,
                IN p_cook_time INT,
                IN p_serving INT,
                IN p_category_recipe_id BIGINT,
                IN p_image_url VARCHAR(255)
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
                    SELECT 1 FROM recipes WHERE id = p_recipe_id
                ) THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Gagal Update: Resep dengan ID tersebut tidak ditemukan.';
                ELSEIF p_category_recipe_id IS NOT NULL
                    AND NOT EXISTS (
                        SELECT 1 FROM recipe_categories WHERE id = p_category_recipe_id
                    ) THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Gagal Update: ID kategori resep tidak valid.';
                END IF;

                UPDATE recipes
                SET
                    name = IFNULL(p_title, name),
                    description = IFNULL(p_description, description),
                    steps = IFNULL(p_steps, steps),
                    cook_time = IFNULL(p_cook_time, cook_time),
                    serving = IFNULL(p_serving, serving),
                    recipe_category_id = IFNULL(p_category_recipe_id, recipe_category_id),
                    image_url = IFNULL(p_image_url, image_url)
                WHERE id = p_recipe_id;

                IF v_is_error = 0 THEN
                    SELECT CONCAT(
                        'Resep dengan ID ',
                        p_recipe_id,
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
        DB::unprepared('DROP PROCEDURE IF EXISTS edit_recipe_procedure');
    }
};