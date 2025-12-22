<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared("
            DROP PROCEDURE IF EXISTS create_recipe_procedure;

            CREATE PROCEDURE create_recipe_procedure(
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

                IF p_title IS NULL OR TRIM(p_title) = '' THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Gagal: Judul resep tidak boleh kosong.';
                ELSEIF p_description IS NULL OR TRIM(p_description) = '' THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Gagal: Deskripsi resep tidak boleh kosong.';
                ELSEIF p_steps IS NULL OR TRIM(p_steps) = '' THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Gagal: Langkah-langkah resep tidak boleh kosong.';
                ELSEIF p_cook_time IS NULL OR p_cook_time <= 0 THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Gagal: Waktu memasak harus lebih dari nol.';
                ELSEIF p_serving IS NULL OR p_serving <= 0 THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Gagal: Porsi harus lebih dari nol.';
                ELSEIF p_category_recipe_id IS NULL OR p_category_recipe_id <= 0 THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Gagal: ID kategori harus diisi.';
                ELSEIF NOT EXISTS (
                    SELECT 1 FROM recipe_category WHERE id = p_category_recipe_id
                ) THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Gagal: ID kategori tidak ditemukan.';
                END IF;

                INSERT INTO recipes (
                    name, description, steps,
                    cook_time, serving, recipe_category_id, image_url, created_at, updated_at
                ) VALUES (
                    p_name, p_description, p_steps,
                    p_cook_time, p_serving, p_category_recipe_id, p_image_url, NOW(), NOW()
                );

                IF v_is_error = 0 THEN
                    SELECT CONCAT(
                        'Resep \"', p_name,
                        '\" berhasil ditambahkan dengan ID ',
                        v_MaxID
                    ) AS ResultMessage;
                ELSE
                    SELECT CONCAT(
                        'TRANSAKSI GAGAL: ',
                        v_error_message
                    ) AS ErrorDetail;
                END IF;
            END
        ");
    }

    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS create_recipe_procedure");
    }
};