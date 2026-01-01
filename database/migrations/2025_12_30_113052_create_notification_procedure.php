<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared("
            DROP PROCEDURE IF EXISTS create_notification_procedure;

            CREATE PROCEDURE create_notification_procedure(
                IN p_title VARCHAR(255),
                IN p_subject VARCHAR(255),
                IN p_message TEXT,
                IN p_date DATETIME,
                IN p_status VARCHAR(50),
                IN p_user_id INT
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

                IF p_user_id IS NULL OR p_user_id <= 0 THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Gagal Simpan: ID User tidak valid.';

                ELSEIF p_title IS NULL OR p_title = '' THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Gagal Simpan: Judul tidak boleh kosong.';

                ELSEIF NOT EXISTS (SELECT 1 FROM users WHERE id = p_user_id) THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Gagal Simpan: User ID tidak terdaftar di sistem.';

                ELSE
                    INSERT INTO notifications (
                        title, 
                        subject, 
                        message, 
                        date, 
                        status, 
                        user_id, 
                        created_at, 
                        updated_at
                    ) VALUES (
                        p_title, 
                        p_subject, 
                        p_message, 
                        p_date, 
                        p_status, 
                        p_user_id, 
                        NOW(), 
                        NOW()
                    );

                    IF v_is_error = FALSE THEN
                        SET v_new_id = LAST_INSERT_ID();
                        SELECT CONCAT(
                            'Success: Notifikasi berhasil dibuat dengan ID ',
                            v_new_id
                        ) AS ResultMessage;
                    END IF;
                END IF;

                IF v_is_error = TRUE THEN
                    SELECT CONCAT(
                        'TRANSAKSI GAGAL KARENA ERROR SQL: ',
                        v_error_message
                    ) AS ErrorDetail;
                END IF;

            END;
        ");
    }

    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS create_notification_procedure;");
    }
};
