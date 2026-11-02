# IMM UINSA - Sistem Manajemen Perkaderan

Sistem informasi perkaderan Ikatan Mahasiswa Muhammadiyah (IMM) berbasis Laravel dengan sistem otentikasi multi-peran (*Role-Based Access Control*), alur persetujuan aktivasi akun, onboarding pengisian biodata wajib, dan dashboard terdedikasi untuk masing-masing peran.

> 📖 **Dokumentasi Terkait:** Untuk memahami latar belakang, 4 pilar pergerakan, aturan validasi, dan diagram alur bisnis lengkap, silakan baca [**Panduan Proses Bisnis (BUSINESS.md)**](BUSINESS.md).

---

## 🌟 Fitur Utama

- **4 Peran Pengguna (Role-Based Access Control):**
  - **Admin:** Pengelola sistem utama (manajemen semua pengguna, aktivasi akun, penetapan peran).
  - **Mentor:** Supervisi proses perkaderan, pembinaan fasilitator, dan evaluasi capaian.
  - **Fasilitator:** Pendampingan kelompok kader binaan, pemeriksaan tugas, dan laporan berkala.
  - **Kader:** Pengisian biodata, akses modul materi, dan pengumpulan tugas perkaderan.
- **Registrasi Publik Terbatas:**
  - Registrasi publik hanya diizinkan untuk peran **Kader** dan **Fasilitator**.
  - Akun yang baru mendaftar secara default berstatus `is_active = false` (menunggu persetujuan/aktivasi Admin).
  - Akun Admin dan Mentor di-generate secara eksklusif via seeder / panel Admin.
- **Onboarding Biodata Wajib (Middleware Enforced):**
  - Fasilitator dan Kader yang login pertama kali otomatis diarahkan ke halaman `/biodata/complete`.
  - Akses dashboard dikunci sampai biodata lengkap diserahkan.
- **Dashboard Terdedikasi:**
  - `/dashboard` otomatis mengarahkan pengguna ke dashboard spesifik berdasarkan perannya.
- **Modern Tech Stack:**
  - Laravel (PHP 8.3+)
  - Tailwind CSS v4 via Vite
  - Blade & Inertia React Templates
  - Pest Test Suite (100% test coverage untuk alur peran & biodata)

---

## 📋 Prasyarat Sistem

Pastikan perangkat Anda telah terpasang:
- **PHP** >= 8.3 atau 8.4 (dengan ekstensi: `pdo_sqlite` atau `pdo_mysql`, `mbstring`, `openssl`, `curl`)
- **Composer** (v2+)
- **Node.js** (v20+) & **npm** (atau **pnpm**)
- **Git**

---

## 🚀 Panduan Menjalankan Proyek (Setup Guide)

Ikuti langkah-langkah berikut di terminal untuk menjalankan aplikasi dari awal:

### 1. Clone Repositori

```bash
git clone https://github.com/F1ru14/IMMUinsa.git
cd IMMUinsa
```

### 2. Pasang Dependensi PHP & JavaScript

```bash
# Pasang paket Composer
composer install

# Pasang paket Node.js
npm install
```

### 3. Salin Konfigurasi Environment

```bash
cp .env.example .env
```

Buat kunci aplikasi (*App Key*):
```bash
php artisan key:generate
```

### 4. Konfigurasi Database & Jalankan Migrasi

Secara default, aplikasi menggunakan database **SQLite**. Buat file database jika belum ada:

```bash
touch database/database.sqlite
```

Jalankan migrasi tabel dan seeding data default:
```bash
php artisan migrate --seed
```

### 5. Buat Symbolic Link Storage (Untuk Upload Foto Avatar)

```bash
php artisan storage:link
```

### 6. Jalankan Server Pengembangan (Development Server)

Anda dapat menjalankan server backend dan frontend sekaligus dengan perintah:

```bash
composer run dev
```

*Atau* jika ingin menjalankannya pada terminal terpisah:

```bash
# Terminal 1: Backend Laravel
php artisan serve

# Terminal 2: Frontend Vite
npm run dev
```

Buka browser dan akses aplikasi di: **[http://localhost:8000](http://localhost:8000)**

---

## 🔑 Akun Bawaan (Default Seeded Accounts)

Setelah menjalankan `php artisan migrate --seed`, Anda dapat langsung masuk menggunakan **username** dan password berikut:

| Peran (Role) | Username | Password | Status / Keterangan |
| :--- | :--- | :--- | :--- |
| **Kader (Uji Coba Flow)** | `kader` | `password` | **Aktif (Belum Isi Biodata)** 👈 *Gunakan untuk mencoba alur lengkap!* |
| **Admin** | `admin` | `password` | Aktif (Pengelola sistem) |
| **Mentor (Entrepreneur)** | `mentor_entrepreneur` | `password` | Aktif (Mentor kewirausahaan) |
| **Mentor (Politisi)** | `mentor_politisi` | `password` | Aktif (Mentor kebijakan publik) |
| **Mentor (Akademisi)** | `mentor_akademisi` | `password` | Aktif (Mentor riset/kampus) |
| **Mentor (Pengabdi)** | `mentor_pengabdi` | `password` | Aktif (Mentor persyarikatan) |
| **Fasilitator (4 Bidang)** | `fasilitator_[bidang]` | `password` | Aktif (`entrepreneur`, `politisi`, `akademisi`, `pengabdi`) |

> Untuk menguji peran **Kader** atau **Fasilitator**, Anda dapat melakukan registrasi baru melalui menu **Register**, kemudian Admin dapat mengaktifkannya di database atau dashboard.

---

## 🧪 Menjalankan Pengujian (Automated Tests)

Aplikasi ini dilengkapi pengujian otomatis dengan **Pest PHP**:

```bash
# Jalankan seluruh unit & feature test
php artisan test --compact

# Atau jalankan Pest secara langsung
vendor/bin/pest
```

Format kode sesuai standar Laravel Pint:
```bash
vendor/bin/pint --format agent
```

---

## 📁 Struktur Direktori Utama

```
app/
├── Actions/Fortify/          # Logika registrasi & manajemen auth pengguna
├── Enums/Role.php            # Enum peran (Admin, Mentor, Fasilitator, Kader)
├── Http/
│   ├── Controllers/
│   │   ├── BiodataController.php    # Form & penyimpanan biodata kader/fasilitator
│   │   └── DashboardController.php  # Router pusat dashboard berbasis role
│   └── Middleware/
│       ├── EnsureBiodataCompleted.php # Force redirect jika biodata kosong
│       └── RoleMiddleware.php         # Proteksi rute berdasarkan peran
├── Models/
│   ├── Biodata.php           # Model profil biodata (1-to-1 dengan User)
│   └── User.php              # Model pengguna terintegrasi role & status
database/
├── migrations/               # Skema tabel users dan biodatas
└── seeders/                  # Seeder akun awal Admin & Mentor
resources/
└── views/
    ├── layouts/app.blade.php # Layout utama Blade dengan Tailwind CSS
    ├── auth/                 # Halaman status approval akun
    ├── biodata/              # Form onboarding biodata
    └── dashboards/           # View dashboard masing-masing peran
routes/
└── web.php                   # Rute web dengan proteksi middleware
```

---

## 📄 Lisensi

Proyek ini didistribusikan di bawah lisensi [MIT License](LICENSE).

