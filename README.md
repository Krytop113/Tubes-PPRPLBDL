# Laravel 12 Tugas Besar PPRPLBDL
Ini merupakan instruksi ketika akan menginisialisasi bagaimana caranya untuk setup terlebih dahulu.
Caranya adalah :
## Install dependencies
Pastikan composer dan php sudah terinstall terlebih dahulu di file, dan dapat memulai langkah langkahnya

1. Jalankan composer terlebih dahulu dependencies yang dibutuhkan
```bash
composer install
```
2. Jalankan npm untuk kebutuhan javascript nya
```bash
npm install
```
## Set up environment
Copy `.env.example` dan ubah namanya menjadi `.env` (dapat dilakukan manual atau melalui terminal):
```bash
cp .env.example .env
```
Setelah itu, setup env ke bagian database dan temukan
DB_CONNECTION=sqlite
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=laravel
# DB_USERNAME=root
# DB_PASSWORD=

ubah connection nya menjadi mysql
dan ubah port, namadatabase, username, dan password nya (pastikan tidak tersebar)

## Generate application key
Lalu run untuk mendapatkan kunci applikasi nya
```bash
php artisan key:generate
```
## Run migrations
Ketika .env sudah benar dan dipastikan tidak ada yang salah, bisa lanjutkan melakukan migrasi ke dalam database
```bash
php artisan migrate --seed
```

## Run the application
Ketika sudah, dapat langsung melakukan run server nya dengan perintah berikut:
```bash
php artisan serve
```
This will start the server at `http://localhost:8000` by default. You can access the application in your web browser by navigating to that URL.
