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

### 1. Clone Repository
```bash
git clone <repository-url>
cd "Helpdesk System"
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Setup Environment
```bash
cp .env.example .env
php artisan key:generate
```

Edit file `.env` dan sesuaikan konfigurasi database:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=helpdesk_db
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Buat Database
Buat database MySQL dengan nama sesuai yang ada di `.env`

### 5. Jalankan Migration dan Seeder
```bash
php artisan migrate
php artisan db:seed
```

### 6. Buat Storage Link

Laravel memerlukan symbolic link (symlink) untuk mengakses file yang disimpan di folder `storage/app/public` melalui URL publik.

#### Cara 1: Menggunakan Artisan Command (Recommended)
```bash
php artisan storage:link
```

#### Cara 2: Manual (Jika Artisan Command Gagal)

**Windows (Command Prompt sebagai Administrator):**
```cmd
mklink /D "public\storage" "storage\app\public"
```

**Linux/Mac:**
```bash
ln -s ../storage/app/public public/storage
```

#### Verifikasi Storage Link

Setelah membuat symlink, verifikasi dengan perintah `ls`:

**Windows (PowerShell):**
```powershell
ls public\storage
```

**Linux/Mac:**
```bash
ls -la public/storage
```

**Hasil yang diharapkan:**
- Jika symlink berhasil, akan menampilkan isi folder `storage/app/public` (tickets, messages, dll)
- Jika symlink gagal, akan muncul error "No such file or directory" atau folder kosong

**Cek apakah symlink sudah ada:**
```bash
# Windows PowerShell
ls public | Select-Object Name, LinkType

# Linux/Mac
ls -la public/ | grep storage
```

Jika symlink sudah ada, Anda akan melihat output seperti:
```
lrwxrwxrwx 1 user user   21 Jan 26 12:00 storage -> ../storage/app/public
```

**Troubleshooting:**
- Jika symlink gagal dibuat, pastikan folder `storage/app/public` sudah ada
- Pastikan folder `public` memiliki permission write
- Di Windows, jalankan Command Prompt sebagai Administrator
- Jika symlink sudah ada tapi tidak berfungsi, hapus dulu lalu buat ulang:
  ```bash
  # Hapus symlink yang ada
  rm public/storage  # Linux/Mac
  rmdir public\storage  # Windows
  
  # Buat ulang
  php artisan storage:link
  ```

#### Cara 3: Symlink dengan Path Berbeda (Project dan Public Terpisah)

**Contoh Kasus Khusus:**

Jika project Laravel dan public folder berada di path berbeda seperti berikut:
- **Project Laravel**: `/home/exaconet/laravel_ticketone`
- **Public Folder**: `/home/exaconet/public_html/demo-ticketonepro.exaconet.com`

**Langkah-langkah:**

1. **Masuk ke folder public:**
```bash
cd /home/exaconet/public_html/demo-ticketonepro.exaconet.com
```

2. **Buat symlink dengan path absolut:**
```bash
ln -s /home/exaconet/laravel_ticketone/storage/app/public storage
```

3. **Verifikasi dengan `ls`:**
```bash
# Cek apakah symlink berhasil dibuat
ls -la | grep storage
```

**Output yang diharapkan:**
```
lrwxrwxrwx 1 exaconet exaconet 50 Jan 26 12:00 storage -> /home/exaconet/laravel_ticketone/storage/app/public
```

4. **Verifikasi isi folder storage:**
```bash
# Masuk ke folder storage
cd storage

# List isi folder
ls -la
```

**Output yang diharapkan:**
```
total 8
drwxr-xr-x 2 exaconet exaconet 4096 Jan 26 12:00 .
drwxr-xr-x 3 exaconet exaconet 4096 Jan 26 12:00 ..
drwxr-xr-x 2 exaconet exaconet 4096 Jan 26 12:00 tickets
drwxr-xr-x 2 exaconet exaconet 4096 Jan 26 12:00 messages
```

5. **Verifikasi path target symlink:**
```bash
# Kembali ke folder public
cd /home/exaconet/public_html/demo-ticketonepro.exaconet.com

# Cek target symlink
readlink -f storage
```

**Output yang diharapkan:**
```
/home/exaconet/laravel_ticketone/storage/app/public
```

6. **Test akses file melalui browser:**
Setelah symlink berhasil, file yang diupload akan dapat diakses via URL:
```
http://demo-ticketonepro.exaconet.com/storage/tickets/nama-file.jpg
```

**Troubleshooting:**

Jika symlink gagal atau tidak berfungsi:

1. **Cek apakah folder target ada:**
```bash
ls -la /home/exaconet/laravel_ticketone/storage/app/public
```

2. **Cek permission folder:**
```bash
ls -ld /home/exaconet/laravel_ticketone/storage/app/public
ls -ld /home/exaconet/public_html/demo-ticketonepro.exaconet.com
```

3. **Hapus symlink yang rusak dan buat ulang:**
```bash
# Hapus symlink yang ada
rm /home/exaconet/public_html/demo-ticketonepro.exaconet.com/storage

# Buat ulang
cd /home/exaconet/public_html/demo-ticketonepro.exaconet.com
ln -s /home/exaconet/laravel_ticketone/storage/app/public storage

# Verifikasi lagi
ls -la | grep storage
```

4. **Jika symlink sudah ada tapi tidak berfungsi, cek dengan:**
```bash
# Cek apakah symlink broken
file storage
# Output harus: "storage: symbolic link to /home/exaconet/laravel_ticketone/storage/app/public"

# Jika output "broken symbolic link", berarti path target salah atau tidak ada
```

**Catatan Penting:**
- Pastikan folder `/home/exaconet/laravel_ticketone/storage/app/public` sudah ada
- Pastikan permission folder memungkinkan akses read
- Di shared hosting, pastikan symlink diizinkan oleh provider
- Setelah membuat symlink, pastikan folder `storage/app/public` memiliki permission 755 atau 775

#### Cara 4: Symlink Umum dengan Path Berbeda (Project dan Public Terpisah)

Jika folder `public` Laravel berada di lokasi berbeda dari root project (misalnya di shared hosting atau struktur folder khusus), gunakan path absolut:

**Contoh Struktur:**
```
/home/user/
├── laravel-project/          # Root project Laravel
│   ├── app/
│   ├── storage/
│   │   └── app/
│   │       └── public/
│   └── ...
└── public_html/              # Public folder (berbeda path)
    └── index.php
```

**Windows (Command Prompt sebagai Administrator):**
```cmd
# Masuk ke folder public
cd C:\path\to\public_html

# Buat symlink dengan path absolut
mklink /D "storage" "C:\path\to\laravel-project\storage\app\public"
```

**Linux/Mac:**
```bash
# Masuk ke folder public
cd /home/user/public_html

# Buat symlink dengan path absolut
ln -s /home/user/laravel-project/storage/app/public storage
```

**Verifikasi dengan ls:**
```bash
# Windows PowerShell
cd C:\path\to\public_html
ls storage

# Linux/Mac
cd /home/user/public_html
ls -la storage
```

**Hasil yang diharapkan:**
- Folder `storage` muncul di `public_html`
- Isi folder `storage` sama dengan `laravel-project/storage/app/public`
- Symlink menunjukkan path target yang benar

**Cek symlink target:**
```bash
# Windows PowerShell
(Get-Item public_html\storage).Target

# Linux/Mac
readlink -f public_html/storage
# atau
ls -la public_html/ | grep storage
```

**Menggunakan Path Relatif (Alternatif):**

Jika folder public masih dalam satu drive/partition yang sama, bisa menggunakan path relatif:

**Contoh struktur:**
```
/home/user/
├── laravel-project/
│   └── storage/app/public/
└── public_html/
```

**Linux/Mac:**
```bash
cd /home/user/public_html
ln -s ../laravel-project/storage/app/public storage
```

**Verifikasi:**
```bash
ls -la public_html/ | grep storage
# Output: lrwxrwxrwx 1 user user 35 Jan 26 12:00 storage -> ../laravel-project/storage/app/public
```

BISA JUGA PAKAI INI JIKA PATH LARAVEL DAN PUBLIC BERBEDA

ln -s /home/usernamecpanel/laravel/storage/app/public /home/usernamecpanel/public_html/storage

**Catatan Penting:**
- Pastikan path absolut benar dan folder `storage/app/public` sudah ada
- Di shared hosting, pastikan symlink diizinkan oleh provider
- Jika symlink tidak diizinkan, gunakan alternatif dengan mengubah konfigurasi di `.env`:
  ```env
  FILESYSTEM_DISK=public
  ```
  Dan pastikan `config/filesystems.php` sudah dikonfigurasi dengan benar

### 7. Jalankan Server
```bash
php artisan serve
```

Akses aplikasi di: `http://localhost:8000`

## Akun Default

Setelah menjalankan seeder, gunakan akun berikut:

### Admin
- Email: `admin@helpdesk.com`
- Password: `password`

### Teknisi
- Email: `teknisi1@helpdesk.com`
- Password: `password`

### User
- Email: `user1@helpdesk.com`
- Password: `password`

## Struktur Project

```
├── app/
│   ├── Exports/          # Excel export classes
│   ├── Http/
│   │   ├── Controllers/  # Controllers
│   │   ├── Middleware/   # Custom middleware
│   │   └── Requests/     # Form requests
│   ├── Models/           # Eloquent models
│   └── Policies/         # Authorization policies
├── database/
│   ├── migrations/       # Database migrations
│   └── seeders/          # Database seeders
├── resources/
│   └── views/           # Blade templates
├── routes/
│   └── web.php          # Web routes
└── public/
    └── css/             # CSS files
```

## Role dan Permission

### Admin
- Mengelola semua user (CRUD)
- Melihat semua tiket
- Assign tiket ke teknisi
- Mengubah status tiket
- Generate laporan
- Export Excel/PDF

### Teknisi
- Melihat tiket yang ditugaskan
- Update status tiket yang ditugaskan
- Chat dengan user/admin
- Melihat dashboard dengan statistik tiket

### User
- Membuat tiket baru
- Melihat tiket sendiri
- Chat pada tiket
- Melihat status tiket

## Fitur Detail

### Sistem Tiket
- Status: Open, In Progress, Solved, Closed
- Prioritas: Low, Medium, High, Urgent
- Kategori: Hardware, Software, Network, Other
- Lampiran file
- Timeline aktivitas

### Chat/Messaging
- Chat berbasis tiket
- Lampiran file pada pesan
- Timestamp dan status read
- Notifikasi pesan baru

### Notifikasi
- Notifikasi tiket baru
- Notifikasi perubahan status
- Notifikasi pesan baru
- Badge counter di navbar
- Polling setiap 30 detik

### Laporan
- Filter berdasarkan periode, status, teknisi
- Export ke Excel (.xlsx)
- Export ke PDF
- Layout profesional dengan header dan footer

## Development

### Menambah Fitur Baru

1. Buat migration jika perlu:
```bash
php artisan make:migration create_table_name
```

2. Buat model:
```bash
php artisan make:model ModelName
```

3. Buat controller:
```bash
php artisan make:controller ControllerName
```

4. Buat policy jika perlu:
```bash
php artisan make:policy ModelNamePolicy
```

5. Tambahkan routes di `routes/web.php`

6. Buat views di `resources/views/`

## License

Proyek ini dibuat untuk keperluan pembelajaran dan dapat digunakan secara bebas.

## Support

Untuk pertanyaan atau bantuan, silakan buat issue di repository ini.
