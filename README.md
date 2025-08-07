````markdown
# ECHO-Market

Platform edukatif untuk jual beli barang daur ulang sekaligus meningkatkan kesadaran lingkungan.

## 🚀 Fitur Utama

- Jual beli barang daur ulang
- Forum edukasi lingkungan
- Manajemen pengguna
- Checkout & histori transaksi

## 🛠️ Requirements

Pastikan Anda sudah menginstall:

- PHP >= 8.0
- Composer
- MySQL / MariaDB
- Node.js & npm (atau yarn)
- Laravel 10+

## ⚙️ Langkah Setup di Lokal

### 1. Clone Repository

```bash
git clone https://github.com/Gentahal/ECHO-Market.git
cd ECHO-Market
````

### 2. Install Dependency

```bash
composer install
npm install
```

### 3. Salin File Environment

```bash
cp .env.example .env
```

### 4. Generate APP Key

```bash
php artisan key:generate
```

### 5. Setup Database

Edit file `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=echo_market
DB_USERNAME=root
DB_PASSWORD=
```

Lalu jalankan:

```bash
php artisan migrate
# Jika ada seeder:
# php artisan db:seed
```

### 6. Compile Frontend

```bash
npm run dev
# atau untuk production:
# npm run build
```

### 7. Jalankan Server Lokal

```bash
php artisan serve
```

Buka di browser: [http://localhost:8000](http://localhost:8000)


