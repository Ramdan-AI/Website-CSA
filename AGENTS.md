# AGENTS.md

Panduan untuk **AI coding agents** dan kontributor otomatis yang bekerja pada repositori **Website CSA** (Laravel 12). Dokumen ini menjelaskan konteks project, perintah penting, pola kode, dan batasan yang harus dipatuhi.

> Untuk gambaran produk & setup, lihat [`README.md`](README.md). Dokumen ini fokus pada **cara bekerja di dalam kode**.

---

## 1. Ringkasan Project

Sistem informasi organisasi **Creative Student Association (CSA)**: website publik + panel admin berbasis peran. Nama project di `.env`: `web-csa`. Repository: `github.com/Ramdan-AI/Website-CSA` (branch `main`).

**Stack inti:** Laravel 12 · PHP 8.2+ · MySQL 8 (testing: SQLite in-memory) · Blade · Tailwind CSS 4 · Vite 7 · Alpine.js · Chart.js · GSAP/Lenis (landing).

**Bahasa:** Komentar & pesan UI **Bahasa Indonesia**; nama kelas/variabel/fungsi **Bahasa Inggris**.

---

## 2. Perintah Wajib (Runbook)

Selalu jalankan dari root project.

```bash
# Setup awal
composer install
cp .env.example .env && php artisan key:generate
php artisan migrate:fresh --seed
php artisan storage:link
npm install && npm run build

# Development (semua service: serve + queue + pail + vite)
composer dev

# Verifikasi sebelum mengakhiri pekerjaan
php artisan test                 # WAJIB hijau
./vendor/bin/pint                # format gaya kode
```

**Tidak ada script `lint`** meskipun disebut di bagian desain responsif di bawah — gunakan `./vendor/bin/pint` untuk PHP. (Jika ditambahkan nanti, perbarui dokumen ini.)

---

## 3. Aturan Penting (Guardrails)

- **Jangan mengubah `phpunit.xml` DB ke MySQL.** Test berjalan di SQLite `:memory:` — jaga kompatibilitas SQL lintas driver (hindari sintaks MySQL-only di kode aplikasi; gunakan query builder/`when()`).
- **`WithoutModelEvents` aktif di `DatabaseSeeder`.** Seeder TIDAK memicu model events (`creating`/`updating`). Model yang meng-generate `slug` (Member, Division, News, Project) **harus di-set slug-nya eksplisit** di seeder, jika tidak akan error `Field 'slug' doesn't have a default value`.
- **Migrasi sudah dikonsolidasikan** (49 → 27 file). Skema final ada di file `create_*`. Jangan menambah migrasi alter untuk kolom yang belum pernah dirilis — edit langsung file `create_*` (project belum production). Migrasi alter baru hanya untuk perubahan setelah deployment.
- **Otorisasi lewat middleware route**, bukan kurung manual di controller: pakai `->middleware(['can:<permission>'])`.
- **Jangan commit rahasia** (`.env`, kredensial). `.env` sudah di-`.gitignore`.
- **Jangan ubah file `vendor/` atau `node_modules/`.**
- **Password default** akun seeded adalah `12345678` — hanya untuk data awal, jangan diganti di seeder tanpa alasan jelas.

---

## 4. Arsitektur & Peta Kode

```
app/Http/Controllers/Admin/       → panel admin (1 controller per modul)
app/Http/Controllers/Frontend/    → halaman publik (Home, News, Project, Gallery, Image)
app/Http/Controllers/Auth/        → autentikasi (Laravel UI)
app/Http/Requests/                → Form Request (validasi per modul)
app/Http/Middleware/              → role, role.match, registration.open
app/Models/                       → 23 Eloquent model
app/Support/                      → AdminNavigation, LogRetentionService, LogsActivity, ResolvesStorageUrl
app/Providers/                    → AppServiceProvider (Gate), DashboardServiceProvider (view composer)
config/admin.php                  → menu sidebar, judul topbar, palet warna
routes/web.php                    → semua route (publik + grup admin ber-prefix {roleSlug})
routes/console.php                → command & scheduler
resources/views/components/       → komponen Blade (admin/, form/, public/, ui/)
resources/views/pages/            → halaman
```

**Routing admin** memakai prefix dinamis `{roleSlug}` (mis. `/ketua/dashboard`, `/anggota/my-attendance`). Nama route ber-prefix `admin.`. Contoh: `admin.members.index`, `admin.member.calendar`.

### Konsep kunci

1. **RBAC dua lapis.** Role (`roles`) → permission (`permissions` via `role_permission`). `Gate::define()` didaftarkan dinamis di `AppServiceProvider::boot()` berdasarkan daftar `$requiredPermissions`. Role `super-admin` otomatis lolos semua (`Role::hasPermission()` short-circuit).
2. **`RoleMatchMiddleware`** (`role.match`) memvalidasi `{roleSlug}` di URL == role user. `RoleMiddleware` (`role`) membatasi by slug eksplisit.
3. **`AdminNavigation`** menyusun sidebar dari `config/admin.php`, difilter by `roles` + `permissions`. Menambah item menu = edit `config/admin.php` (tidak ada view hardcode).
4. **`LogsActivity` trait** mencatat audit otomatis via `created`/`updated`/`deleted`. Field default yang disembunyikan: `activityHiddenFields()`. Tambahkan trait ke model yang perlu diaudit.
5. **`ResolvesStorageUrl` trait** membangun URL storage yang mengikuti host request (bukan `APP_URL`) — dipakai agar gambar tampil di domain lokal.

---

## 5. Pola yang Harus Diikuti

### Controller admin (pola CRUD standar)
```php
// index: validasi query, filter via when(), paginate()->withQueryString()
public function index(Request $request)
{
    $validated = $request->validate(['search' => ['nullable', 'string', 'max:100']]);

    $items = Model::query()
        ->when($validated['search'] ?? null, fn ($q, $s) => $q->where('name', 'like', "%{$s}%"))
        ->latest()
        ->paginate(15)
        ->withQueryString();

    return view('pages.modul.index', compact('items'));
}
```
- Redirect dengan flash: `->with('success', '...')` atau `->with('error', '...')`.
- Validasi: **selalu** Form Request untuk create/update; validasi query di method `index`.

### Route (otorisasi wajib)
```php
Route::middleware(['can:manage-xyz'])->group(function () {
    Route::resource('xyz', XyzController::class)->except(['show']);
});
```
Urutkan route read-only umum **setelah** resource CRUD agar `{param}` tidak menangkap `create`/`edit` (lihat pola di `routes/web.php` untuk `news`).

### Model
- Tambah trait sesuai kebutuhan: `LogsActivity` (audit), `SoftDeletes`, `ResolvesStorageUrl` (URL gambar).
- Slug unik: generate di hook `creating`/`updating` dengan pengecekan `withTrashed()` (lihat `Member`, `Project`).
- Casts: gunakan method `casts()` (bukan property `$casts`) untuk konsistensi di model baru.
- Scope query bernama (`scopeActive`, `scopePublished`, `scopeFeatured`).

### Blade
- Gunakan komponen yang ada: `<x-admin.table>`, `<x-admin.panel>`, `<x-admin.stat-strip>`, `<x-admin.empty-state>`, `<x-form.input>`, `<x-form.select>`, `<x-form.textarea>`, `<x-form.date>`, `<x-form.file-crop>`, `<x-admin.modal.delete>`.
- Komponen `form.input` menangani `old()` + error otomatis — jangan tulis markup input manual bila ada komponennya.
- Selalu `<div class="overflow-x-auto">` untuk tabel (sudah ditangani `<x-admin.table>`).

---

## 6. Konvensi Desain Responsif (WAJIB)

Berlaku untuk semua perubahan UI:

1. **Mobile-first**: gunakan prefiks `sm: md: lg: xl:`.
2. **Touch target**: elemen interaktif minimal 44×44px (`min-h-[44px]`).
3. **Tabel responsif**: selalu bungkus dengan `overflow-x-auto`.
4. **Dark mode**: gunakan utilities dark mode Tailwind (`dark:`); pastikan kontras memadai. Admin memakai `@custom-variant dark (&:where(.dark, .dark *))`.
5. **Theme**: default mengikuti sistem; preferensi disimpan di `localStorage` key `theme`.
6. **Verifikasi visual**: uji di simulator mobile Chrome (iPhone 12/13/14/15/16 Pro) untuk perubahan layout.

---

## 7. Testing

- Framework: **PHPUnit 11**, test di `tests/Feature` & `tests/Unit`, trait `RefreshDatabase`.
- **Setiap fitur baru/perbaikan bug harus disertai test** yang gagal sebelum fix dan lolos sesudahnya.
- Test membuat datanya sendiri (tidak bergantung pada seeder) — lihat `AttendanceFlowTest`, `PositionMembersTest`.
- Jalankan: `php artisan test` (atau `php artisan test --filter=NamaTest`).

Contoh kerangka test:
```php
use Illuminate\Foundation\Testing\RefreshDatabase;

class XyzTest extends TestCase
{
    use RefreshDatabase;

    public function test_sesuatu_berhasil(): void
    {
        // arrange → act → assert
        $this->assertTrue(true);
    }
}
```

---

## 8. Database & Migrasi

- Skema final = **27 file** `create_*` di `database/migrations`. Sudah dikonsolidasi; tidak ada migrasi alter.
- Kolom penting: `users.role_id` **nullable**; `members.nim` **wajib & unik**; `attendances` unik per `(event_id, member_id)`; `divisions.has_levels` + `members.level` untuk divisi bertingkat.
- Seeder: `RoleSeeder` → `PermissionSeeder` → `RolePermissionSeeder` → `DivisionSeeder` → `PositionSeeder` → `AdminSeeder` → `MemberSeeder` → `NewsCategorySeeder` → `NewsSeeder` → `SettingSeeder` → `AlbumSeeder` → `GallerySeeder`. **Urutan penting** (`NewsSeeder` butuh user author yang sudah ada).
- `MemberSeeder` membuat akun login untuk setiap anggota: email = dua kata pertama nama (lowercase, tanpa spasi) `@gmail.com`, password `12345678`, role diturunkan dari jabatan.
- Data anggota (NIM) masih **placeholder** (`2026xxxx`) — bisa diganti bila NIM asli tersedia.

---

## 9. Utang Teknis yang Diketahui (Dead Code)

Perlu dibersihkan (jangan diperluas):

- `app/Models/EventRegistration.php`, `app/Http/Requests/EventRegistrationRequest.php`, `app/Http/Controllers/Admin/EventRegistrationController.php`, dan `resources/views/pages/event-registrations/*` — **sisa modul pendaftaran event yang sudah dihapus** (tabel `event_registrations` tidak ada, tidak ada route). Aman dihapus.
- `app/Http/Controllers/Api/*` — placeholder kosong (API belum diimplementasikan; `routes/api.php` tidak ada).
- Model `Event::getBannerUrlAttribute()` memakai `asset('storage/...')` alih-alih trait `ResolvesStorageUrl` — kandidat refactor.

Saat menyentuh area ini, pertimbangkan membersihkan/menyelaraskan.

---

## 10. Definition of Done

Sebuah perubahan dianggap selesai bila:

- [ ] `php artisan test` **lolos** (tanpa regresi).
- [ ] `./vendor/bin/pint` dijalankan pada file yang diubah.
- [ ] Fitur/route baru terlindungi middleware `can:*` yang tepat.
- [ ] UI responsif (mobile-first, dark mode) sesuai [bagian 6](#6-konvensi-desain-responsif-wajib).
- [ ] Prinsip RBAC dihormati (cek role & permission).
- [ ] `README.md` / `AGENTS.md` diperbarui bila mengubah setup, perintah, atau arsitektur.
- [ ] Tidak ada data rahasia yang ter-commit.

---

## 11. Referensi Cepat

| Kebutuhan | Lokasi |
|---|---|
| Tambah item menu sidebar | `config/admin.php` → `menu` |
| Tambah permission baru | `PermissionSeeder` + daftar `$requiredPermissions` di `AppServiceProvider` + `RolePermissionSeeder` |
| Atur judul topbar | `config/admin.php` → `titles` |
| Logika retensi log | `app/Support/LogRetentionService.php` |
| Command terjadwal | `routes/console.php` |
| Thumbnail dinamis | `app/Http/Controllers/Frontend/ImageController.php` |
| Komponen UI | `resources/views/components/` |
