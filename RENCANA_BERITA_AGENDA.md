# Rencana: Berita & Agenda Landing Page

> **Status:** Ditunda (backlog) — belum diimplementasikan.
> Bagian **"Berita & Pengumuman Persyarikatan"** dan **"Agenda Terdekat"** di landing page saat ini masih memakai data placeholder statis di `resources/js/pages/welcome.tsx`. Dokumen ini menyimpan hasil riset dan rancangan agar pengembang berikutnya tinggal mengeksekusi.

---

## 1. Ringkasan Keputusan

| Aspek | Keputusan |
| :--- | :--- |
| Pendekatan | **Opsi B (RSS otomatis) + fallback Opsi A (data statis)** |
| Sumber berita | `https://www.immuinsasby.com/` — situs resmi IMM UIN Sunan Ampel Surabaya |
| Endpoint feed | `https://www.immuinsasby.com/feeds/posts/default?alt=rss` (alternatif: `/rss.xml`) |
| Perubahan database | Tidak ada (tanpa migration/model) |
| Agenda | Tetap statis sampai ada sumber data agenda resmi |

---

## 2. Hasil Verifikasi Sumber (per 1 Nov 2026)

| Aspek | Hasil |
| :--- | :--- |
| Platform | Blogger / Blogspot |
| `/feed/` | ❌ 404 (bukan WordPress) |
| `/feeds/posts/default?alt=rss` | ✅ RSS 2.0 valid |
| `/rss.xml` | ✅ RSS 2.0 valid (isi setara) |
| Item per request | 25 item (arsip total 476) |
| Ukuran payload | ±250 KB |

### Field yang tersedia per item

| Field feed | Dipakai untuk | Catatan |
| :--- | :--- | :--- |
| `title` | Judul kartu | Bersih, tanpa HTML |
| `link` | URL tujuan kartu | Sudah URL absolut |
| `pubDate` | Tanggal kartu | RFC 822 (UTC) → konversi ke `d M Y` WIB |
| `category[]` | Badge kategori | Bisa lebih dari satu (mis. `Agama`, `Artikel`, `Ibn Rusyd`) |
| `media:thumbnail` | Gambar kartu (opsional) | Ukuran `s72-c` → ganti ke `s400`/`w400` |
| `description` | Excerpt | HTML penuh artikel (±1.600–8.200 karakter), wajib di-strip |

### Label kategori asli situs

`Kabar`, `Artikel`, `Opini/Esai`, `Cerpen`, `Puisi`, `Respon`, `BULETIN`, `Tarikh`, serta nama komisariat: `KUF`, `Al-Farabi`, `Leviathan`, `Ibn Rusyd`, `Al-Kindi`, `Febi`, `Saintek`, `Avempace`.

---

## 3. Rancangan Implementasi

### 3.1 Service

Buat `app/Services/PersyarikatanNews.php`:

1. `Http::timeout(4)->get(config('news.feed_url'))`.
2. Parse dengan `SimpleXML`; ambil elemen `channel > item`.
3. Filter kategori berita (lihat §5) dan buang item non-berita (Cerpen/Puisi) bila diaktifkan.
4. Bersihkan excerpt: `strip_tags()` → `html_entity_decode(ENT_QUOTES | ENT_HTML5)` → rapatkan whitespace → `Str::limit(…, 140)`.
5. Normalisasi tanggal: `Carbon::parse($pubDate)->timezone('Asia/Jakarta')->translatedFormat('d M Y')`.
6. Naikkan resolusi thumbnail: `str_replace('/s72-c/', '/s400/', $url)`.
7. Kembalikan array dengan bentuk stabil: `title`, `url`, `source`, `date`, `excerpt`, `category`, `image`.

### 3.2 Cache & Ketahanan

```php
Cache::remember('persyarikatan.news', now()->addMinutes(30), fn () => $this->fetch());
```

- Cache normal ±30 menit.
- Cache cadangan ±24 jam (`persyarikatan.news.backup`) agar saat feed mati konten terakhir tetap tampil.
- Bila fetch gagal dan cache kosong → kembalikan `[]`, UI memakai fallback statis.
- Jangan biarkan exception HTTP/XML bocor ke halaman: bungkus `try/catch`, catat lewat `Log::warning()`.

### 3.3 Konfigurasi

`config/news.php`:

```php
return [
    'feed_url' => env('NEWS_FEED_URL', 'https://www.immuinsasby.com/feeds/posts/default?alt=rss'),
    'item_limit' => (int) env('NEWS_ITEM_LIMIT', 3),
    'categories' => ['Kabar', 'Artikel', 'Opini/Esai'],
    'cache_minutes' => 30,
];
```

Tambahkan `NEWS_FEED_URL` dan `NEWS_ITEM_LIMIT` ke `.env.example` (URL tidak boleh di-hardcode di kode).

### 3.4 Route

```php
Route::get('/', function (PersyarikatanNews $news): Response {
    return Inertia::render('welcome', [
        'news' => $news->latest(),
    ]);
})->name('home');
```

### 3.5 Tampilan (`welcome.tsx`)

- Tipe prop `news: NewsItem[]`, fallback ke konstanta `announcements` bila kosong.
- Kartu ber-`url` dirender sebagai `<a href={item.url} target="_blank" rel="noopener noreferrer">` dengan ikon ↗ (external link) sebagai penanda aksesibilitas.
- Tambahkan atribusi kecil di bawah kartu: **"Sumber: immuinsasby.com"**.
- Pertahankan gaya kartu sekarang: border tipis, badge kategori maroon, judul `group-hover:text-brand`, tanpa drop shadow.
- Tampilkan skeleton/pulse hanya jika kelak memakai deferred props (saat ini tidak perlu).

### 3.6 Test (Pest)

`tests/Feature/LandingNewsTest.php`:

1. `Http::fake()` sukses → halaman 200, judul item feed terlihat.
2. `Http::fake()` gagal/500 → halaman tetap 200, fallback statis tampil.
3. Pastikan tidak ada request keluar saat cache masih hangat.

---

## 4. Risiko & Mitigasi

| Risiko | Mitigasi |
| :--- | :--- |
| Feed mati / format berubah | Parser toleran + cache cadangan 24 jam + fallback statis |
| Latensi cold cache (2–5 detik) | `timeout(4)` + cache 30 menit |
| Excerpt berupa HTML panjang | `strip_tags` + `html_entity_decode` + limit 140 karakter |
| Gambar thumbnail pecah | Naikkan parameter ukuran ke `s400` |
| Isu hak cipta | Hanya judul + kutipan pendek + tautan + atribusi sumber |
| Konten pusat kurang relevan komisariat | Filter kategori + opsi pin 1 berita internal |

---

## 5. Keputusan yang Masih Terbuka

1. **Jumlah kartu:** 3 (layout sekarang) atau 6 (grid 3×2).
2. **Filter kategori:** semua item, atau hanya `Kabar` + `Artikel` + `Opini/Esai`.
3. **Thumbnail:** ditampilkan atau tidak (butuh penyesuaian layout kartu).

> Rekomendasi awal: **3 kartu · filter Kabar/Artikel/Opini · tanpa thumbnail** (paling aman secara visual dan paling ringan).

---

## 6. Agenda Terdekat

Belum ada sumber data agenda resmi (kalender/ICS/feed). Sampai tersedia:

- Tetap memakai array statis `agenda` di `welcome.tsx`.
- Kandidat pengembangan lanjutan: model `Agenda` + CRUD Admin, atau tarik dari Google Calendar/ICS bila pengurus punya kalender publik.
- Bila nanti naik ke pengelolaan mandiri, pertimbangkan sekalian model `Announcement` untuk pengumuman internal komisariat (Opsi C).

---

## 7. Estimasi

±1,5–2 jam untuk berita (service + config + route + UI + test), tanpa mengubah skema database.
