# Website CSA — Creative Student Association

Sistem informasi organisasi **Creative Student Association (CSA)** STMIK Mardira Indonesia: website publik, manajemen anggota, kehadiran/presensi QR, kesekretariatan (surat & notulen), keuangan, publikasi, media, dan audit log — semuanya berbasis peran (role-based).

Dibangun dengan **Laravel 12**, **PHP 8.2+**, **MySQL**, **Blade**, **Tailwind CSS 4**, **Vite**, dan **Alpine.js**.

---

## Daftar Isi

- [Fitur Utama](#fitur-utama)
- [Arsitektur & Tech Stack](#arsitektur--tech-stack)
- [Peran & Hak Akses](#peran--hak-akses)
- [Persyaratan Sistem](#persyaratan-sistem)
- [Instalasi (Development)](#instalasi-development)
- [Akun Bawaan](#akun-bawaan)
- [Perintah Umum](#perintah-umum)
- [Testing](#testing)
- [Struktur Direktori](#struktur-direktori)
- [Skema Database](#skema-database)
- [Konvensi Kode](#konvensi-kode)
- [Deployment](#deployment)
- [Troubleshooting](#troubleshooting)
- [Kontribusi](#kontribusi)
- [Lisensi](#lisensi)

---

## Fitur Utama

### Publik (tanpa login)
- **Landing page** — hero dinamis dari galeri, statistik anggota/divisi, kepengurusan, karya anggota, kegiatan, dan CTA pendaftaran.
- **Direktori anggota** — daftar anggota publik dengan pencarian nama & filter divisi (paginated).
- **Berita** — daftar dan detail berita (thumbnail, galeri gambar, kategori).
- **Showcase proyek** — galeri karya anggota (tech stack, repo, demo).
- **Thumbnail on-the-fly** — resize gambar otomatis via `GET /img/{width}/{path}` dengan cache disk + header immutable.
- **Pendaftaran anggota** — formulir pendaftaran mandiri (bisa dibuka/ditutup lewat pengaturan; mode manual atau terjadwal).

### Dashboard berbasis peran
Setiap pengguna diarahkan ke dashboard sesuai rolenya (`/{roleSlug}/dashboard`):

| Role | Fokus dashboard |
|---|---|
| Ketua / Wakil Ketua / Super Admin | Keuangan, agenda, ringkasan organisasi |
| Sekretaris | Surat, notulen, berita, agenda |
| Bendahara | Pemasukan, pengeluaran, laporan keuangan |
| Ketua Divisi / Wakil Ketua Divisi | Anggota divisi sendiri, rekap kehadiran divisi |
| Anggota | Agenda & presensi pribadi, riwayat kehadiran |

### Modul Admin
- **Data Master** — Divisi (dengan skills & penanda divisi bertingkat) dan Jabatan (dengan batas `max_members`).
- **Keanggotaan** — CRUD anggota + akun login otomatis, penugasan divisi/jabatan, level (untuk divisi bertingkat), import/ekspor rekap.
- **Kehadiran (Presensi)** — buka/tutup sesi, kode unik & QR code, scan, presensi mandiri, presensi manual (bulk), status (present/permit/sick/absent), ekspor CSV.
- **Kegiatan** — Acara & Study Club, status (draft/published/closed), banner, link pendaftaran.
- **Kesekretariatan** — Surat masuk/keluar (nomor referensi, lampiran) dan Notulen rapat (peserta dari anggota).
- **Keuangan** — Pemasukan & Pengeluaran, laporan keuangan terpadu per tahun (saldo berjalan), ekspor CSV.
- **Publikasi** — Berita + Kategori (via modal), Dokumen (dengan pencatatan unduhan).
- **Media** — Album dan Galeri (penanda gambar hero untuk landing page).
- **Komunikasi** — Pengumuman (kategori info/penting/urgent, pin, rentang tampil, notifikasi topbar).
- **Showcase** — Proyek karya anggota; semua role dapat mengunggah, kepemilikan dijaga controller.
- **Audit** — Log Sistem otomatis (create/update/delete) untuk model ber-`LogsActivity`, dengan retensi & purge terjadwal.

### Pengaturan Sistem
- Profil website (nama, logo, kontak, sosial media, tentang).
- Pendaftaran & komunitas (toggle manual atau rentang tanggal terjadwal, link grup WhatsApp).
- Role & Izin (matriks permission per role, prinsip least privilege).
- Retensi log (aktif/nonaktif, periode 7–90 hari, purge manual/otomatis).

---

## Arsitektur & Tech Stack

| Lapisan | Teknologi |
|---|---|
| Framework | Laravel 12 |
| Bahasa | PHP 8.2+ |
| Database | MySQL 8 (produksi), SQLite in-memory (testing) |
| Templating | Blade + komponen anonim |
| Styling | Tailwind CSS 4 (via `@tailwindcss/vite`), dark mode |
| Interaktivitas | Alpine.js + Chart.js |
| Animasi | GSAP + ScrollTrigger + Lenis (landing page) |
| Ikon | Lucide (dipakai di `resources/js/admin.js`) |
| Build tool | Vite 7 |
| Auth | Laravel UI (`laravel/ui`) + Gate/Policy berbasis permission |

**Pola arsitektur:**
- **Role-Based Access Control (RBAC)** dua lapis: *role* (dari jabatan) dan *permission* (matriks per role). Otorisasi lewat `Gate::define()` dinamis di `AppServiceProvider`.
- **`RoleMatchMiddleware`** memvalidasi `{roleSlug}` di URL sesuai role user (mencegah akses lintas-role lewat manipulasi URL).
- **`AdminNavigation`** membangun menu sidebar secara dinamis dari `config/admin.php`, difilter by role & permission.
- **`LogsActivity`** (trait) mencatat audit otomatis lewat model events.
- **`LogRetentionService`** menyatukan logika retensi log (dipakai controller + scheduled command).

---

## Peran & Hak Akses

Role bawaan (lihat `database/seeders/RoleSeeder.php`):

| Slug | Nama | Cakupan |
|---|---|---|
| `super-admin` | Super Admin | Akses penuh (semua permission otomatis) |
| `ketua` | Ketua | Struktur organisasi, pengaturan, moderasi |
| `wakil-ketua` | Wakil Ketua | Operasional tanpa ubah struktur/pengaturan |
| `sekretaris` | Sekretaris | Berita, dokumen, surat, notulen, presensi |
| `bendahara` | Bendahara | Keuangan, laporan, presensi (lihat) |
| `ketua-divisi` | Ketua Divisi | Divisi sendiri: anggota & rekap kehadiran |
| `wakil-ketua-divisi` | Wakil Ketua Divisi | Divisi sendiri (tanpa pendaftaran) |
| `anggota` | Anggota | Lihat kegiatan + presensi pribadi |

Daftar permission lengkap ada di `database/seeders/PermissionSeeder.php`; pemetaan role→permission di `database/seeders/RolePermissionSeeder.php`. Otorisasi route memakai middleware `can:<permission>`, mis. `can:manage-members`.

> **Catatan:** `role_id` pada tabel `users` bersifat **nullable** — memungkinkan akun tanpa role (mis. akun teknis), tetapi `RoleMatchMiddleware` akan menolak akses dashboard tanpa role.

---

## Persyaratan Sistem

- **PHP ≥ 8.2** dengan ekstensi: `pdo`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, **`gd`** (untuk thumbnail on-the-fly).
- **Composer 2**
- **Node.js ≥ 18** & **npm**
- **MySQL 8** (atau SQLite untuk sekadar mencoba)
- Opsional: **Laragon** (Windows) / **Herd** / **Valet**

---

## Instalasi (Development)

### 1. Clone & install dependency PHP
```bash
git clone https://github.com/Ramdan-AI/Website-CSA.git
cd Website-CSA
composer install
```

### 2. Environment
```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env`, sesuaikan koneksi database:
```env
APP_NAME="Website CSA"
APP_URL=http://web-csa.test

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=web_csa
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Migrasi & seed database
```bash
php artisan migrate --seed
```
Perintah ini membuat seluruh skema **dan** mengisi data awal (role, permission, divisi, jabatan, pengaturan, akun admin, anggota + akun login, berita, galeri).

Jika ingin mulai bersih:
```bash
php artisan migrate:fresh --seed
```

### 4. Storage link (wajib untuk gambar)
```bash
php artisan storage:link
```

### 5. Build asset frontend
```bash
npm install
npm run build          # produksi
# atau saat development:
npm run dev            # hot-reload Vite
```

### 6. Jalankan aplikasi

**Cara cepat (semua service sekaligus):**
```bash
composer dev
```
Menjalankan: `php artisan serve`, `queue:listen`, `pail` (log), dan `vite` bersamaan.

**Manual:**
```bash
php artisan serve
```

Buka `http://web-csa.test` (sesuai `APP_URL`) atau `http://127.0.0.1:8000`.

---

## Akun Bawaan

Setelah `migrate --seed`:

| Peran | Email | Password |
|---|---|---|
| Super Admin | `admin@csa.local` | `password` |
| Ketua | `nidamutmainah@gmail.com` | `12345678` |
| Seluruh pengurus & anggota | *(2 kata pertama nama + @gmail.com)* | `12345678` |

Akun anggota di-generate otomatis dari `MemberSeeder` (email = dua kata pertama nama, lowercase, tanpa spasi). Contoh: `muhammadramdan@gmail.com`, `fikrimaulana@gmail.com`.

> ⚠️ **Ganti password default sebelum production.** Password default hanya untuk kemudahan onboarding anggota.

**Pendaftaran publik** (`/register`) hanya aktif bila dibuka admin di **Pengaturan → Pendaftaran & Komunitas**. Akun baru otomatis mendapat role `anggota`.

---

## Perintah Umum

```bash
# Database
php artisan migrate                # jalankan migrasi
php artisan migrate:fresh --seed   # reset + seed
php artisan db:seed                # seed ulang (idempotent sebagian)
php artisan db:seed --class=MemberSeeder

# Aplikasi
php artisan serve                  # server development
php artisan optimize:clear         # bersihkan semua cache
php artisan config:cache           # cache konfigurasi (produksi)
php artisan route:list             # lihat semua route

# Log retensi (terjadwal harian)
php artisan activity-log:purge

# Scheduler (produksi) — tambahkan 1 cron entry:
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1

# Frontend
npm run dev                        # Vite dev server
npm run build                      # build produksi

# Kualitas kode
./vendor/bin/pint                  # format kode PHP (Laravel Pint)
./vendor/bin/pint --test           # cek tanpa mengubah
php artisan test                   # jalankan test suite
```

---

## Testing

Test suite memakai **PHPUnit 11** dengan SQLite in-memory (`phpunit.xml`), terisolasi via `RefreshDatabase`.

```bash
php artisan test                    # semua test
php artisan test --filter=AttendanceFlowTest
php artisan test --coverage         # butuh Xdebug/PCOV
```

Cakupan test saat ini (**50+ test**):
- Alur kehadiran & siklus presensi (`AttendanceFlowTest`, `AttendanceLifecycleTest`)
- Presensi mandiri & toggle (`SelfAttendanceButtonTest`, `SelfAttendanceToggleTest`)
- Retensi log (`LogRetentionTest`)
- Pendaftaran & level divisi (`RegisterLevelTest`)
- Jadwal pendaftaran (`RegistrationScheduleTest`)
- RekreasI slug & email anggota setelah delete (`MemberRecreateTest`, `MemberSlugTest`)
- Modal kategori berita, notifikasi topbar, komponen Alpine

---

## Struktur Direktori

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/          # Semua controller panel admin (per modul)
│   │   ├── Api/            # Controller API (placeholder)
│   │   ├── Auth/           # Login, Register, Reset password, Verifikasi
│   │   └── Frontend/       # Home, News, Project, Gallery, Image (publik)
│   ├── Middleware/
│   │   ├── RoleMiddleware.php            # alias: role
│   │   ├── RoleMatchMiddleware.php       # alias: role.match
│   │   └── RegistrationOpenMiddleware.php# alias: registration.open
│   └── Requests/           # Form Request (validasi per modul)
├── Models/                 # Eloquent models (23 model; lihat catatan dead code)
├── Providers/
│   ├── AppServiceProvider.php        # registrasi Gate permission dinamis
│   └── DashboardServiceProvider.php  # view composer dashboard
└── Support/
    ├── AdminNavigation.php           # menu sidebar dinamis
    ├── LogRetentionService.php       # logika retensi log
    ├── LogsActivity.php              # trait audit otomatis
    └── ResolvesStorageUrl.php        # trait URL storage (host-aware)

config/
└── admin.php               # konfigurasi menu, judul topbar, warna

database/
├── migrations/             # 27 file migrasi (skema final)
└── seeders/                # Data awal & akun

resources/
├── css/
│   ├── app.css             # Tailwind (publik)
│   └── admin.css           # Tailwind (admin, dark variant)
├── js/
│   ├── app.js              # GSAP + Lenis (landing)
│   └── admin.js            # Lucide icons + Alpine
└── views/
    ├── components/         # Komponen Blade (admin/, form/, public/, ui/)
    ├── layouts/            # admin, app, landing
    ├── pages/              # Halaman admin & publik
    └── auth/               # Halaman autentikasi

routes/
├── web.php                 # Semua route web (publik + admin)
└── console.php             # Command & scheduler

tests/
├── Feature/                # Test fitur (endpoint, alur)
└── Unit/                   # Test unit
```

---

## Skema Database

Skema final dikelola dalam **27 file migrasi** (migrasi alter sudah dikonsolidasikan ke `create_*` masing-masing tabel). Tabel utama:

| Domain | Tabel |
|---|---|
| Akses | `users`, `roles`, `permissions`, `role_permission` |
| Keanggotaan | `members`, `divisions`, `positions` |
| Kegiatan | `events`, `attendances` |
| Kesekretariatan | `letters`, `minutes`, `minute_member` |
| Keuangan | `incomes`, `expenses` |
| Publikasi | `news`, `news_categories`, `documents`, `document_downloads` |
| Media & karya | `albums`, `galleries`, `projects`, `project_member` |
| Komunikasi & audit | `announcements`, `activity_logs`, `settings` |
| Infra Laravel | `cache`, `cache_locks`, `jobs`, `job_batches`, `failed_jobs`, `sessions`, `password_reset_tokens` |

Relasi penting:
- `users.role_id` **nullable** → `roles` (restrict on delete).
- `members.user_id` → `users` (null on delete); `members.nim` **unique & wajib**.
- `members.division_id`/`position_id` → nullable (pengurus inti lintas divisi).
- `attendances` unik per `(event_id, member_id)`.
- `divisions.has_levels` menandai divisi bertingkat (mis. Pemrograman) yang memakai kolom `members.level`.

---

## Konvensi Kode

- **Bahasa**: komentar & pesan UI dalam Bahasa Indonesia; nama kelas/variabel dalam Bahasa Inggris.
- **Gaya PHP**: ikuti **Laravel Pint** (`./vendor/bin/pint`) — standar PSR-12 + preset Laravel.
- **Form Request**: setiap modul CRUD punya Form Request tersendiri di `app/Http/Requests/`.
- **Otorisasi**: gunakan middleware `can:<permission>` di route, bukan pengecekan manual di controller.
- **Audit**: tambahkan trait `LogsActivity` pada model yang perlu dicatat perubahannya.
- **Slug**: model yang punya slug (Member, Division, News, Project) meng-generate slug unik lewat hook `creating`/`updating`.
- **Seeding**: `DatabaseSeeder` memakai `WithoutModelEvents` — jika menambah seeder yang bergantung pada hook model (slug, dll.), **set kolom tersebut secara eksplisit** di seeder.
- **Blade**: gunakan komponen yang tersedia (`<x-admin.table>`, `<x-form.input>`, dst.); pastikan responsif (mobile-first, target sentuh ≥ 44px) dan mendukung dark mode.

Detail konvensi desain responsif ada di [`AGENTS.md`](AGENTS.md).

---

## Deployment

### Checklist produksi
```bash
# 1. Build optimasi
composer install --no-dev --optimize-autoloader
npm ci && npm run build

# 2. Konfigurasi environment
#    APP_ENV=production, APP_DEBUG=false, isi kredensial DB, MAIL, dll.

# 3. Migrasi (tanpa interaksi)
php artisan migrate --force

# 4. Cache produksi
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# 5. Storage link + permission
php artisan storage:link
#   Pastikan storage/ dan bootstrap/cache/ writable oleh web server

# 6. Jalankan scheduler via cron
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

### Catatan penting
- **Ganti semua password default.**
- **Set `APP_DEBUG=false`** dan `APP_ENV=production`.
- **Backup database** sebelum menjalankan migrasi.
- Aktifkan **HTTPS** dan set `APP_URL` ke domain produksi.
- Worker queue (`queue:work`) diperlukan bila memakai job async — saat ini queue default `database`.
- Ekstensi **GD** wajib untuk fitur resize gambar.

---

## Troubleshooting

| Masalah | Solusi |
|---|---|
| Gambar/thumbnail tidak tampil | Jalankan `php artisan storage:link`; pastikan ekstensi PHP `gd` aktif. |
| `Field 'slug' doesn't have a default value` saat seed | Seeder yang bergantung hook model harus set `slug` eksplisit (karena `WithoutModelEvents`). |
| Error 403 setelah login | Role user belum cocok dengan `{roleSlug}` di URL, atau user belum punya `role_id`. |
| Aset tidak ter-update setelah edit CSS/JS | Jalankan `npm run dev` (atau `npm run build`); hard-refresh browser. |
| Perubahan config tak berefek | `php artisan config:clear` lalu `config:cache`. |
| Redirect ke halaman pendaftaran tertutup | Buka pendaftaran di **Pengaturan → Pendaftaran & Komunitas**. |

---

## Kontribusi

1. Buat branch dari `main`: `git checkout -b feat/nama-fitur`.
2. Ikuti konvensi kode (jalankan `./vendor/bin/pint`).
3. Tulis/perbarui test untuk fitur baru.
4. Pastikan `php artisan test` hijau.
5. Ajukan Pull Request dengan deskripsi perubahan yang jelas.

**Konvensi commit** (disarankan Conventional Commits):
```
feat: tambah ekspor kehadiran per divisi
fix: perbaiki mapping role wakil ketua divisi
docs: perbarui README
refactor: konsolidasi file migrasi
```

---

## Lisensi

Proyek ini dikembangkan untuk **Creative Student Association (CSA)** STMIK Mardira Indonesia.
Lisensi mengikuti MIT kecuali dinyatakan lain.
