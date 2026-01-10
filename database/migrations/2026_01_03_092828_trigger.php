<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ingredient
        DB::unprepared('
            CREATE TRIGGER log_ingredient_insert AFTER INSERT ON ingredients
            FOR EACH ROW BEGIN
                INSERT INTO log (user_id, pengguna, title, action, created_at, updated_at)
                VALUES ("SYSTEM", "System", "Ingredient Created", CONCAT("Menambah bahan: ", NEW.name), NOW(), NOW());
            END;
        ');

        DB::unprepared('
            CREATE TRIGGER log_ingredient_update AFTER UPDATE ON ingredients
            FOR EACH ROW BEGIN
                DECLARE deskripsi_perubahan TEXT DEFAULT "";

                IF OLD.name <> NEW.name THEN 
                    SET deskripsi_perubahan = CONCAT(deskripsi_perubahan, "Nama: ", OLD.name, " -> ", NEW.name, ". ");
                END IF;

                IF OLD.stock_quantity <> NEW.stock_quantity THEN 
                    SET deskripsi_perubahan = CONCAT(deskripsi_perubahan, "Stok: ", OLD.stock_quantity, " -> ", NEW.stock_quantity, ". ");
                END IF;

                IF OLD.price_per_unit <> NEW.price_per_unit THEN 
                    SET deskripsi_perubahan = CONCAT(deskripsi_perubahan, "Harga: ", OLD.price_per_unit, " -> ", NEW.price_per_unit, ". ");
                END IF;

                IF deskripsi_perubahan <> "" THEN
                    INSERT INTO log (user_id, pengguna, title, action, created_at, updated_at)
                    VALUES ("SYSTEM", "System", "Ingredient Updated", CONCAT("Perubahan pada bahan ", OLD.name, ": ", deskripsi_perubahan), NOW(), NOW());
                END IF;
            END;
        ');

        DB::unprepared('
            CREATE TRIGGER log_ingredient_delete AFTER DELETE ON ingredients
            FOR EACH ROW BEGIN
                INSERT INTO log (user_id, pengguna, title, action, created_at, updated_at)
                VALUES ("SYSTEM", "System", "Ingredient Deleted", CONCAT("Menghapus bahan: ", OLD.name), NOW(), NOW());
            END;
        ');

        // Recipe
        DB::unprepared('
            CREATE TRIGGER log_recipe_insert AFTER INSERT ON recipes
            FOR EACH ROW BEGIN
                INSERT INTO log (user_id, pengguna, title, action, created_at, updated_at)
                VALUES ("SYSTEM", "System", "Recipe Created", CONCAT("Menambah resep: ", NEW.name), NOW(), NOW());
            END;
        ');

        DB::unprepared('
            CREATE TRIGGER log_recipe_update AFTER UPDATE ON recipes
            FOR EACH ROW BEGIN
                DECLARE msg TEXT DEFAULT "";

                IF OLD.name <> NEW.name THEN 
                    SET msg = CONCAT(msg, "Nama resep berubah dari ", OLD.name, " menjadi ", NEW.name);
                END IF;

                IF OLD.cook_time <> NEW.cook_time THEN 
                    SET msg = CONCAT(msg, "Waktu masak: ", OLD.cook_time, "mnt -> ", NEW.cook_time, "mnt. ");
                END IF;

                IF msg <> "" THEN
                    INSERT INTO log (user_id, pengguna, title, action, created_at, updated_at)
                    VALUES ("SYSTEM", "System", "Recipe Updated", msg, NOW(), NOW());
                END IF;
            END;
        ');
        DB::unprepared('
            CREATE TRIGGER log_recipe_delete AFTER DELETE ON recipes
            FOR EACH ROW BEGIN
                INSERT INTO log (user_id, pengguna, title, action, created_at, updated_at)
                VALUES ("SYSTEM", "System", "Recipe Deleted", CONCAT("Menghapus resep: ", OLD.name), NOW(), NOW());
            END;
        ');

        // Coupon
        DB::unprepared('
            CREATE TRIGGER log_coupon_insert AFTER INSERT ON coupons
            FOR EACH ROW BEGIN
                INSERT INTO log (user_id, pengguna, title, action, created_at, updated_at)
                VALUES ("SYSTEM", "System", "Coupon Created", CONCAT("Menambah kupon: ", NEW.title), NOW(), NOW());
            END;
        ');

        DB::unprepared('
            CREATE TRIGGER log_coupon_update AFTER UPDATE ON coupons
            FOR EACH ROW BEGIN
                IF OLD.discount_percentage <> NEW.discount_percentage THEN
                    INSERT INTO log (user_id, pengguna, title, action, created_at, updated_at)
                    VALUES ("SYSTEM", "System", "Coupon Updated", 
                    CONCAT("Diskon kupon ", OLD.title, " berubah: ", OLD.discount_percentage, "% -> ", NEW.discount_percentage, "%"), 
                    NOW(), NOW());
                END IF;
            END;
        ');

        DB::unprepared('
            CREATE TRIGGER log_coupon_delete AFTER DELETE ON coupons
            FOR EACH ROW BEGIN
                INSERT INTO log (user_id, pengguna, title, action, created_at, updated_at)
                VALUES ("SYSTEM", "System", "Coupon Deleted", CONCAT("Menghapus kupon: ", OLD.title), NOW(), NOW());
            END;
        ');
    }

    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS log_ingredient_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS log_ingredient_update');
        DB::unprepared('DROP TRIGGER IF EXISTS log_ingredient_delete');
        DB::unprepared('DROP TRIGGER IF EXISTS log_recipe_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS log_recipe_update');
        DB::unprepared('DROP TRIGGER IF EXISTS log_recipe_delete');
        DB::unprepared('DROP TRIGGER IF EXISTS log_coupon_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS log_coupon_update');
        DB::unprepared('DROP TRIGGER IF EXISTS log_coupon_delete');
    }
};
