# Platform Digital Perkaderan IMM UINSA

Platform digital perkaderan Ikatan Mahasiswa Muhammadiyah (IMM) UIN Sunan Ampel Surabaya. Aplikasi ini dibangun dengan Laravel, Inertia.js, dan React untuk mengelola registrasi anggota, persetujuan akun, onboarding biodata, pemetaan minat kader, penugasan perkaderan, evaluasi fasilitator, hingga penghubungan kader dengan mentor senior.

> 📖 **Dokumentasi Terkait:** Untuk memahami latar belakang, 4 pilar pergerakan, matriks peran, aturan validasi, dan diagram alur bisnis lengkap, silakan baca [**Panduan Proses Bisnis (BUSINESS.md)**](BUSINESS.md).

---

## 🌟 Fitur Utama

- **Landing Page Publik:**
    - Menampilkan profil gerakan, 4 rumpun bidang, agenda, dan berita terkini dari feed resmi [immuinsasby.com](https://www.immuinsasby.com/).
    - Berita di-cache otomatis dengan cadangan (_backup cache_) agar halaman tetap tampil saat feed sedang tidak tersedia.
- **Otentikasi & Keamanan (Laravel Fortify):**
    - Login menggunakan **username** (bukan email).
    - Registrasi publik, reset password, dan autentikasi dua faktor (2FA/TOTP).
    - Halaman pengaturan profil, keamanan, dan tampilan (mode terang/gelap).
- **4 Peran Pengguna (Role-Based Access Control):**
    - **Admin:** Verifikasi pendaftaran, aktivasi/penolakan akun, dan pengelolaan seluruh pengguna.
    - **Mentor:** Melihat portofolio kader binaan, termasuk biodata, tugas yang selesai, dan catatan evaluasi fasilitator.
    - **Fasilitator:** Melihat kader binaan, memantau tugas, dan memberikan catatan evaluasi.
    - **Kader:** Mengisi biodata, memilih minat dan fokus bidang, mengerjakan tugas, serta memilih mentor setelah memenuhi syarat.
- **Registrasi Publik Terbatas:**
    - Registrasi hanya menerima peran **Kader**, **Fasilitator**, dan **Mentor** (whitelist `Role::publicRoles()`).
    - Akun **Kader** langsung aktif (`is_active = true`) setelah mendaftar.
    - Akun **Fasilitator** dan **Mentor** berstatus `is_active = false` dan menunggu persetujuan Admin.
    - Akun **Admin** hanya dibuat melalui seeder dan tidak tersedia melalui registrasi publik.
- **Onboarding Biodata Wajib (Middleware Enforced):**
    - Kader dan Fasilitator yang belum melengkapi biodata otomatis diarahkan ke `/biodata/complete`.
    - Akses dashboard dan modul perkaderan dikunci sampai biodata diserahkan.
    - Akun yang masih menunggu persetujuan diarahkan ke halaman **Pending Approval**.
- **Perjalanan Kader Berjenjang:**
    - Kader memilih 1–3 minat, lalu sistem memetakan minat tersebut ke salah satu dari 4 rumpun bidang.
    - Kader memilih fasilitator pendamping sesuai rumpun bidangnya.
    - Tersedia 3 tugas perkaderan untuk setiap rumpun (total 12 tugas).
    - Pemilihan mentor senior terbuka otomatis setelah kader menyelesaikan minimal 3 tugas.
- **Dashboard Terdedikasi:**
    - `/dashboard` mengarahkan pengguna ke dashboard sesuai perannya masing-masing.
    - Admin dapat menyetujui atau menolak akun yang menunggu persetujuan langsung dari dashboard.
- **Tech Stack Modern:**
    - Laravel 13 (PHP 8.3+)
    - Inertia.js v3 + React 19
    - Tailwind CSS v4 via Vite
    - Laravel Fortify, Laravel Wayfinder
    - Pest PHP, Laravel Pint, dan Larastan

---

## 📋 Prasyarat Sistem

Pastikan perangkat Anda telah terpasang:

- **PHP** >= 8.3 (dengan ekstensi: `pdo_sqlite` atau `pdo_mysql`, `mbstring`, `openssl`, `curl`)
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

Buat kunci aplikasi (_App Key_):

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

> Aplikasi menggunakan driver `database` untuk session, cache, dan queue. Pastikan migrasi dijalankan sebelum server dinyalakan.

Jika ingin mengulang dari database kosong:

```bash
php artisan migrate:fresh --seed
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

_Atau_ jika ingin menjalankannya pada terminal terpisah:

```bash
# Terminal 1: Backend Laravel
php artisan serve

# Terminal 2: Frontend Vite
npm run dev
```

Buka browser dan akses aplikasi di: **[http://localhost:8000](http://localhost:8000)**

> **Alternatif:** Perintah `composer run setup` dapat dipakai untuk memasang dependensi, menyalin `.env`, membuat app key, menjalankan migrasi, serta build frontend. Setelah itu jalankan `php artisan db:seed` bila ingin memuat akun dan data contoh.

---

## 🔑 Akun Bawaan (Default Seeded Accounts)

Setelah menjalankan `php artisan migrate --seed`, Anda dapat langsung masuk menggunakan **username** dan password berikut. Seluruh akun bawaan menggunakan password: `password`.

| Peran / Kelompok                   | Username                                                              | Status / Keterangan                                            |
| :--------------------------------- | :-------------------------------------------------------------------- | :------------------------------------------------------------- |
| **Admin**                          | `admin`                                                               | Aktif (pengelola sistem)                                       |
| **Akun Uji Factory**               | `test`                                                                | Aktif sebagai Admin (dibuat oleh `DatabaseSeeder`)             |
| **Mentor Umum**                    | `mentor`                                                              | Aktif                                                          |
| **Kader (Uji Coba Flow)**          | `kader`                                                               | Aktif, **belum mengisi biodata** 👈 _cocok untuk mencoba alur_ |
| **Fasilitator — Entrepreneur**     | `fasilitator_entrepreneur`, `fasilitator_entrepreneur_2`              | Aktif, sudah memiliki biodata                                  |
| **Fasilitator — Politisi**         | `fasilitator_politisi`, `fasilitator_politisi_2`                      | Aktif, sudah memiliki biodata                                  |
| **Fasilitator — Akademisi**        | `fasilitator_akademisi`, `fasilitator_akademisi_2`                    | Aktif, sudah memiliki biodata                                  |
| **Fasilitator — Pengabdi**         | `fasilitator_pengabdi`, `fasilitator_pengabdi_2`                      | Aktif, sudah memiliki biodata                                  |
| **Mentor — Entrepreneur**          | `mentor_entrepreneur`, `mentor_entrepreneur_2`                        | Aktif, sudah memiliki biodata                                  |
| **Mentor — Politisi**              | `mentor_politisi`, `mentor_politisi_2`                                | Aktif, sudah memiliki biodata                                  |
| **Mentor — Akademisi**             | `mentor_akademisi`, `mentor_akademisi_2`                              | Aktif, sudah memiliki biodata                                  |
| **Mentor — Pengabdi**              | `mentor_pengabdi`, `mentor_pengabdi_2`                                | Aktif, sudah memiliki biodata                                  |

> Untuk menguji peran **Kader**, **Fasilitator**, atau **Mentor**, Anda juga dapat melakukan registrasi baru melalui menu **Register**. Akun **Kader langsung aktif**, sedangkan akun **Fasilitator** dan **Mentor** menunggu persetujuan Admin. Admin dapat menyetujui atau menolak pendaftaran melalui **Admin Dashboard → Menunggu Persetujuan**.

---

## 🧭 Alur Perkaderan Kader

1. **Registrasi & Aktivasi** — Kader mendaftar dengan username dan password, lalu akun langsung aktif.
2. **Onboarding Biodata** — Kader wajib melengkapi nama lengkap, nomor WhatsApp, alamat, tanggal lahir, jenis kelamin, asal komisariat, dan foto avatar (opsional).
3. **Pemetaan Minat** — Kader memilih 1–3 minat dari 20 pilihan yang tersedia. Sistem memetakan minat tersebut ke salah satu rumpun: **Entrepreneur**, **Politisi**, **Akademisi**, atau **Pengabdi Persyarikatan**. Jika minat tersebar merata, kader diminta memilih rumpun utama secara manual.
4. **Pemilihan Fasilitator** — Sistem merekomendasikan fasilitator yang sesuai dengan rumpun bidang kader.
5. **Penugasan** — Kader mengerjakan 3 tugas perkaderan sesuai rumpunnya dan mengirimkan catatan penyelesaian.
6. **Evaluasi Fasilitator** — Fasilitator pendamping dapat memberikan catatan evaluasi yang tersimpan sebagai rekam jejak kader.
7. **Unlock Mentor** — Setelah 3 tugas selesai, kader dapat memilih mentor senior di bidangnya.
8. **Pendampingan Mentor** — Mentor melihat portofolio kader binaan, termasuk biodata, daftar tugas selesai, dan catatan evaluasi fasilitator.

---

## 🧪 Menjalankan Pengujian & Pemeriksaan Kode

Aplikasi ini dilengkapi pengujian otomatis dengan **Pest PHP**:

```bash
# Jalankan seluruh unit & feature test
php artisan test --compact

# Atau jalankan Pest secara langsung
vendor/bin/pest

# Jalankan seluruh rangkaian pemeriksaan (lint, PHPStan, test)
composer run test
```

Format kode sesuai standar Laravel Pint:

```bash
# Format hanya file yang berubah
vendor/bin/pint --dirty --format agent

# Format seluruh kode
vendor/bin/pint --format agent
```

Pemeriksaan TypeScript dan frontend:

```bash
npm run types:check
npm run check
```

---

## 📁 Struktur Direktori Utama

```text
app/
├── Actions/Fortify/              # Registrasi & reset password pengguna
├── Concerns/                     # Aturan validasi password & profil
├── Enums/
│   ├── Role.php                  # Peran: Admin, Mentor, Fasilitator, Kader
│   ├── FocusArea.php             # 4 rumpun bidang perkaderan
│   ├── Interest.php              # 20 minat kader & pemetaan ke rumpun
│   └── Komisariat.php            # Daftar komisariat IMM UINSA
├── Http/
│   ├── Controllers/
│   │   ├── BiodataController.php        # Form & penyimpanan biodata
│   │   ├── DashboardController.php      # Router dashboard berbasis role
│   │   ├── FasilitatorController.php    # Catatan evaluasi kader
│   │   ├── KaderJourneyController.php   # Alur fokus, fasilitator, tugas, mentor
│   │   └── Settings/                    # Profil, keamanan, tampilan
│   ├── Middleware/
│   │   ├── EnsureBiodataCompleted.php   # Redirect jika biodata belum lengkap
│   │   └── RoleMiddleware.php           # Proteksi rute berdasarkan peran
│   └── Responses/                       # Redirect Inertia untuk login/register
├── Models/
│   ├── User.php                  # Pengguna, role, status, relasi fasilitator/mentor
│   ├── Biodata.php               # Profil biodata (1-to-1 dengan User)
│   ├── Task.php                  # Modul tugas per rumpun bidang
│   ├── KaderTask.php             # Riwayat penyelesaian tugas kader
│   └── KaderEvaluation.php       # Catatan evaluasi fasilitator
├── Services/
│   └── PersyarikatanNews.php     # Pengambilan & cache berita landing page
config/
├── fortify.php                   # Konfigurasi autentikasi Fortify
└── news.php                      # Konfigurasi feed berita
database/
├── migrations/                   # Skema users, biodatas, tasks, evaluations, dll.
├── factories/                    # Factory User & Biodata
└── seeders/                      # Akun awal, alur perkaderan, dan 12 tugas
resources/
├── js/
│   ├── pages/                    # Halaman Inertia React (auth, dashboard, settings)
│   ├── components/               # Komponen UI & layout
│   ├── layouts/                  # Layout aplikasi & autentikasi
│   ├── routes/                   # Fungsi rute hasil generate Wayfinder
│   └── actions/                  # Fungsi controller hasil generate Wayfinder
└── views/
    ├── layouts/app.blade.php     # Layout Blade untuk halaman onboarding
    ├── biodata/                  # Form onboarding biodata
    ├── kader/                    # Halaman pemilihan fokus, fasilitator, mentor
    ├── dashboards/               # View dashboard masing-masing peran
    └── components/               # Komponen Blade (back button, header, logo)
routes/
├── web.php                       # Rute utama & proteksi middleware
└── settings.php                  # Rute pengaturan pengguna
tests/
├── Feature/                      # Feature test alur auth, role, biodata, kader
└── Unit/                         # Unit test
```

---

## ⚙️ Konfigurasi Tambahan

### Berita Landing Page

Berita diambil dari feed RSS resmi IMM UINSA dan dapat disesuaikan melalui `.env`:

```dotenv
NEWS_FEED_URL="https://www.immuinsasby.com/feeds/posts/default?alt=rss"
NEWS_ITEM_LIMIT=3
NEWS_CACHE_MINUTES=30
```

### Database

Default aplikasi menggunakan SQLite. Untuk menggunakan MySQL/MariaDB, sesuaikan variabel berikut di `.env`:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=imm_uinsa
DB_USERNAME=root
DB_PASSWORD=
```

### Frontend Build

Jika perubahan frontend tidak muncul atau muncul error **"Unable to locate file in Vite manifest"**, jalankan:

```bash
npm run build
```

atau jalankan mode pengembangan:

```bash
npm run dev
```

---

## 📄 Lisensi

Proyek ini didistribusikan di bawah lisensi [MIT License](LICENSE).
