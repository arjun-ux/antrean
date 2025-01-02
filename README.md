# Aplikasi Laravel - Panduan Instalasi

Aplikasi ini dibangun menggunakan framework [Laravel](https://laravel.com/) dan dapat dijalankan baik di lingkungan lokal (development) maupun produksi.

## Prasyarat

Sebelum melanjutkan instalasi, pastikan Anda sudah memiliki prasyarat berikut:

- PHP >= 8.0
- Composer (untuk mengelola dependensi PHP)
- MySQL / PostgreSQL / SQLite (sesuai dengan konfigurasi yang Anda pilih)
- Node.js dan NPM (untuk pengelolaan front-end assets)
- Git (untuk meng-clone repositori)

## Instalasi di Lokal (Development)

1. **Clone Repositori**

   Pertama, clone repositori ke dalam direktori lokal Anda.

   ```bash
   git clone https://github.com/arjun-ux/antrean.git
   cd antrean

2. **Instalasi Dependensi PHP**
    Instalasi dependensi PHP menggunakan Composer.

    ```bash 
    composer install

    # jika didalam production bisa menggunakan 
    composer install --optimize-autoloader --no-dev

3. **Buat File .env**
    Rename file .env.example ke .env

4. **Setting Database**
    Masuke Ke File .env

    DB_CONNECTION=mysql // sesuaikan dengan koenksinya
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=antrian // sesuaikan dengan nama database yang akan digunakana
    DB_USERNAME= sesuaikan aja
    DB_PASSWORD= sesuaikan aja dengan password mysql nya (misal)

5. **Generate Key Aplikasi**

   ```bash
   php artisan key:generate

6. **Migrasi Database**

   ```bash
   php artisan migrate
