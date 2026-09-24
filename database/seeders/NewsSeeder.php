<?php

namespace Database\Seeders;

use App\Models\News;
use App\Models\NewsCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class NewsSeeder extends Seeder
{
    private const THUMBNAILS = [
        'galleries/csa-gts-e32oinvsa.png',
        'galleries/csa-gts-fo43289.png',
        'galleries/csa-gts-jkr352084.png',
        'galleries/csa-gts-jlr2eu9084.png',
        'galleries/csa-eraport-jrkfedw.png',
        'galleries/fdlskajr093284loifdjas.png',
        'galleries/ffjkh249834.png',
        'galleries/jflkwu250923uj4rlkqdj.png',
    ];

    public function run(): void
    {
        $categories = NewsCategory::pluck('id', 'name')->toArray();
        $author = User::whereHas('role', fn ($q) => $q->where('slug', 'admin'))->first()
            ?? User::first();

        if (empty($categories)) {
            $this->command->warn('NewsCategorySeeder harus dijalankan terlebih dahulu.');

            return;
        }

        $items = [
            [
                'title' => 'Gathering Tech & Startup (GTS) 2025: Menghubungkan Mahasiswa dengan Industri',
                'category' => 'Kegiatan',
                'content' => "CSA kembali menggelar Gathering Tech & Startup (GTS) 2025. Acara ini menghadirkan praktisi industri dari berbagai perusahaan teknologi yang berbagi pengalaman seputar dunia kerja, tren startup, dan pengembangan karier di bidang IT.\n\nRatusan mahasiswa STMIK Mardira Indonesia antusias mengikuti sesi talk show, workshop, hingga portfolio clinic. Melalui kegiatan ini, CSA berharap dapat menjadi jembatan antara dunia kampus dan kebutuhan industri.",
                'daysAgo' => 3,
            ],
            [
                'title' => 'Workshop UI/UX Design: Membangun Produk Digital yang Ramah Pengguna',
                'category' => 'Kegiatan',
                'content' => "Divisi Multimedia CSA menyelenggarakan workshop UI/UX design selama dua hari. Peserta belajar mulai dari riset pengguna, pembuatan wireframe, hingga prototyping menggunakan tools industri terkini.\n\nWorkshop ini menjadi agenda rutin untuk membekali anggota dengan keterampilan desain yang relevan dengan kebutuhan dunia kerja. Seluruh peserta berhasil menyelesaikan studi kasus pembuatan aplikasi mobile sederhana.",
                'daysAgo' => 12,
            ],
            [
                'title' => 'Open Recruitment Pengurus & Anggota CSA Periode 2025/2026 Resmi Dibuka',
                'category' => 'Pengumuman',
                'content' => "CSA membuka pendaftaran anggota baru untuk periode 2025/2026. Terbuka untuk seluruh mahasiswa aktif STMIK Mardira Indonesia dari semua jurusan.\n\nTersedia dua divisi: Divisi Pemrograman dan Divisi Multimedia. Pendaftar akan melalui seleksi administrasi dan wawancara. Jangan lewatkan kesempatan untuk bergabung menjadi bagian dari keluarga besar CSA!",
                'daysAgo' => 20,
            ],
            [
                'title' => 'Tim CSA Borong Juara pada Lomba Competitive Programming Tingkat Nasional',
                'category' => 'Prestasi',
                'content' => "Prestasi membanggakan kembali diraih oleh tim Competitive Programming CSA pada lomba tingkat nasional yang diselenggarakan akhir bulan lalu. Tim yang terdiri dari tiga anggota berhasil menyabet juara kedua setelah melewati babak penyisihan yang ketat.\n\nKeberhasilan ini tidak lepas dari latihan rutin dan pembinaan intensif yang dilakukan divisi pemrograman sepanjang semester.",
                'daysAgo' => 26,
            ],
            [
                'title' => 'CSA Bekerja Sama dengan Kampus dalam Pengembangan Aplikasi E-Raport',
                'category' => 'Pengumuman',
                'content' => "Sebagai bentuk kontribusi nyata kepada kampus, CSA dipercaya membantu pengembangan aplikasi E-Raport yang digunakan oleh seluruh jurusan. Proyek ini melibatkan anggota Divisi Pemrograman dan Multimedia secara kolaboratif.\n\nMelalui proyek ini, anggota mendapatkan pengalaman mengerjakan sistem berskala produksi langsung di lingkungan kampus.",
                'daysAgo' => 34,
            ],
            [
                'title' => 'Rekap Kegiatan Kaderisasi Anggota Baru: Membangun Kebersamaan sejak Awal',
                'category' => 'Kegiatan',
                'content' => "Kaderisasi anggota baru CSA berlangsung meriah dengan rangkaian kegiatan yang seru dan bermanfaat. Mulai dari perkenalan divisi, permainan kolaboratif, hingga sesi mentoring bersama pengurus inti.\n\nKaderisasi menjadi titik awal yang penting untuk menumbuhkan rasa kebersamaan dan semangat berkarya di antara anggota baru sebelum terjun ke proyek-proyek nyata.",
                'daysAgo' => 41,
            ],
        ];

        $created = 0;

        foreach ($items as $i => $item) {
            $thumbnail = self::THUMBNAILS[$i % count(self::THUMBNAILS)];

            News::create([
                'category_id' => $categories[$item['category']] ?? null,
                'user_id' => $author?->id,
                'title' => $item['title'],
                'slug' => Str::slug($item['title']),
                'content' => $item['content'],
                'thumbnail' => $thumbnail,
                'status' => 'published',
                'published_at' => now()->subDays($item['daysAgo'])->startOfDay(),
            ]);
            $created++;
        }

        $this->command->info("NewsSeeder berhasil: {$created} berita dibuat.");
    }
}
