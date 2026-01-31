# Sistem Informasi Helpdesk

Sistem informasi helpdesk berbasis web menggunakan Laravel 12 dengan fitur lengkap untuk manajemen tiket, chat, notifikasi, dan laporan.

## Fitur Utama

- ✅ Authentication multi-role (Admin, Teknisi, User)
- ✅ Dashboard berbeda untuk setiap role
- ✅ Manajemen tiket lengkap (CRUD, assign, update status)
- ✅ Sistem chat/messaging berbasis tiket
- ✅ Notifikasi real-time dengan polling
- ✅ Manajemen user (Admin only)
- ✅ Export laporan ke Excel dan PDF
- ✅ Timeline aktivitas tiket
- ✅ UI/UX modern dengan inspirasi Windows 8
- ✅ Responsive design

## Teknologi

- **Framework:** Laravel 12
- **Database:** MySQL
- **Export:** Maatwebsite Excel, DomPDF
- **Font:** Poppins (Google Fonts)
- **Icons:** Font Awesome 6

## Instalasi

## Instalasi (Production / Docker)

### Quick Start
1.  **Clone Repository**
    ```bash
    git clone <repository-url>
    cd "Helpdesk System"
    ```

2.  **Setup Environment**
    Copy file konfigurasi environment:
    ```bash
    cp src/.env.example src/.env
    ```

3.  **Jalankan Aplikasi**
    ```bash
    docker compose --env-file src/.env up -d --build
    ```
    Perintah ini akan otomatis setup database, install dependencies, dan build assets.

3.  **Akses Aplikasi**
    Buka browser dan akses: `http://localhost:8080` (atau IP server Anda:8080).

### Perintah Penting (Docker)
-   **Cek Status Container**: `docker compose ps`
-   **Lihat Logs**: `docker compose logs -f app`
-   **Matikan Aplikasi**: `docker compose down`

---

## Instalasi Manual (Development / Tanpa Docker)

Jika Anda ingin menjalankan aplikasi secara manual tanpa Docker:

### 1. Masuk ke Folder Source
Semua kode Laravel berada di dalam folder `src`.
```bash
cd src
```

### 2. Install Dependencies
```bash
composer install
npm install && npm run build
```

### 3. Setup Environment
```bash
cp .env.example .env
php artisan key:generate
```

Edit file `.env` dan sesuaikan konfigurasi database.

### 4. Setup Database
Buat database baru di MySQL, lalu jalankan:
```bash
php artisan migrate
php artisan db:seed
```

### 5. Setup Storage
```bash
php artisan storage:link
```

### 6. Jalankan Server
```bash
php artisan serve
```

## License

Proyek ini dibuat untuk keperluan pembelajaran dan dapat digunakan secara bebas.

## Support

Untuk pertanyaan atau bantuan, silakan buat issue di repository ini.
