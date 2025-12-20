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
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306 (sesuaikan port sql nya)
DB_DATABASE= (nama database/schema nya)
DB_USERNAME=root
DB_PASSWORD= (jika ada tulis, jika tidak kosongkan saja)

Sesuaikan dengan format tersebut

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

```bash
npm run dev
```

Buat menjalankan vite agar sesi breeze login dapat berjalan

(dilakukan 2 terminal terpisah, 1 npm run dev yang 1 lagi php artisan serve)
