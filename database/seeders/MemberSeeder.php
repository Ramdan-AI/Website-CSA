<?php

namespace Database\Seeders;

use App\Models\Division;
use App\Models\Member;
use App\Models\Position;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MemberSeeder extends Seeder
{
    public function run(): void
    {
        $divisions = Division::all();
        $positions = Position::all();

        if ($divisions->isEmpty() || $positions->isEmpty()) {
            $this->command->warn('Pastikan DivisionSeeder dan PositionSeeder sudah dijalankan.');
            return;
        }

        $ketua = $positions->firstWhere('name', 'Ketua')?->id;
        $wakilKetua = $positions->firstWhere('name', 'Wakil Ketua')?->id;
        $sekretaris1 = $positions->firstWhere('name', 'Sekretaris 1')?->id;
        $sekretaris2 = $positions->firstWhere('name', 'Sekretaris 2')?->id;
        $bendahara1 = $positions->firstWhere('name', 'Bendahara 1')?->id;
        $bendahara2 = $positions->firstWhere('name', 'Bendahara 2')?->id;

        $ketuaDivisi = $positions->firstWhere('name', 'Ketua Divisi')?->id;
        $wakilKetuaDivisi = $positions->firstWhere('name', 'Wakil Ketua Divisi')?->id;
        $anggota = $positions->firstWhere('name', 'Anggota')?->id;

        $divPemrograman = $divisions->firstWhere('name', 'Divisi Pemrograman')?->id;
        $divMultimedia = $divisions->firstWhere('name', 'Divisi Multimedia')?->id;

        $members = [
            [
                'name' => 'Ahmad Fauzi',
                'division_id' => $divPemrograman,
                'position_id' => $ketua,
                'nim' => '20240001',
                'email' => 'ahmad.fauzi@csa.ac.id',
                'phone' => '081234567890',
                'bio' => 'Mahasiswa Teknik Informatika semester 5. Berpengalaman dalam pengembangan web dan manajemen tim.',
                'linkedin' => 'https://linkedin.com/in/ahmadfauzi',
                'instagram' => 'ahmadfauzi',
                'github' => 'ahmadfauzi',
            ],
            [
                'name' => 'Dewi Lestari',
                'division_id' => $divMultimedia,
                'position_id' => $wakilKetua,
                'nim' => '20240004',
                'email' => 'dewi.lestari@csa.ac.id',
                'phone' => '081234567893',
                'bio' => 'Mahasiswa Desain Komunikasi Visual semester 5. Ahli dalam branding dan strategi komunikasi.',
                'linkedin' => 'https://linkedin.com/in/dewilestari',
                'instagram' => 'dewilestari',
            ],
            [
                'name' => 'Siti Nurhaliza',
                'division_id' => $divPemrograman,
                'position_id' => $sekretaris1,
                'nim' => '20240002',
                'email' => 'siti.nurhaliza@csa.ac.id',
                'phone' => '081234567891',
                'bio' => 'Mahasiswa Sistem Informasi semester 5. Teliti dalam administrasi dan dokumentasi.',
                'instagram' => 'sitinurhaliza',
                'github' => 'sitinurhaliza',
            ],
            [
                'name' => 'Rina Anggraini',
                'division_id' => $divMultimedia,
                'position_id' => $sekretaris2,
                'nim' => '20240006',
                'email' => 'rina.anggraini@csa.ac.id',
                'phone' => '081234567895',
                'bio' => 'Mahasiswa Manajemen semester 3. Terampil dalam pengarsipan dan surat-menyurat.',
            ],
            [
                'name' => 'Eko Prasetyo',
                'division_id' => $divPemrograman,
                'position_id' => $bendahara1,
                'nim' => '20240005',
                'email' => 'eko.prasetyo@csa.ac.id',
                'phone' => '081234567894',
                'bio' => 'Mahasiswa Akuntansi semester 5. Berpengalaman dalam pengelolaan keuangan organisasi.',
                'linkedin' => 'https://linkedin.com/in/ekoprasetyo',
            ],
            [
                'name' => 'Fitri Handayani',
                'division_id' => $divMultimedia,
                'position_id' => $bendahara2,
                'nim' => '20240013',
                'email' => 'fitri.handayani@csa.ac.id',
                'phone' => '081234567898',
                'bio' => 'Mahasiswa Ekonomi semester 3. Membantu pencatatan keuangan dan pembukuan.',
            ],

            [
                'name' => 'Budi Santoso',
                'division_id' => $divPemrograman,
                'position_id' => $ketuaDivisi,
                'nim' => '20240003',
                'email' => 'budi.santoso@csa.ac.id',
                'phone' => '081234567892',
                'bio' => 'Mahasiswa Teknik Komputer semester 5. Fullstack developer dengan spesialisasi Laravel dan React.',
                'github' => 'budisantoso',
            ],
            [
                'name' => 'Anisa Rahma',
                'division_id' => $divPemrograman,
                'position_id' => $wakilKetuaDivisi,
                'nim' => '20240010',
                'email' => 'anisa.rahma@csa.ac.id',
                'phone' => '081234567899',
                'bio' => 'Mahasiswa Teknik Informatika semester 3. Frontend developer dengan fokus pada UI/UX.',
                'github' => 'anisarahma',
            ],
            [
                'name' => 'Fajar Pratama',
                'division_id' => $divPemrograman,
                'position_id' => $anggota,
                'nim' => '20240011',
                'email' => 'fajar.pratama@csa.ac.id',
                'bio' => 'Mahasiswa Teknik Informatika semester 1. Sedang belajar dasar-dasar pemrograman.',
                'github' => 'fajarpratama',
            ],
            [
                'name' => 'Dian Permata',
                'division_id' => $divPemrograman,
                'position_id' => $anggota,
                'nim' => '20240014',
                'email' => 'dian.permata@csa.ac.id',
                'bio' => 'Mahasiswa Sistem Informasi semester 1. Antusias dalam pengembangan aplikasi mobile.',
            ],
            [
                'name' => 'Rizky Aditya',
                'division_id' => $divPemrograman,
                'position_id' => $anggota,
                'nim' => '20240015',
                'email' => 'rizky.aditya@csa.ac.id',
                'bio' => 'Mahasiswa Teknik Komputer semester 3. Fokus pada backend development dan database.',
                'github' => 'rizkyaditya',
            ],

            [
                'name' => 'Gilang Ramadhan',
                'division_id' => $divMultimedia,
                'position_id' => $ketuaDivisi,
                'nim' => '20240007',
                'email' => 'gilang.ramadhan@csa.ac.id',
                'phone' => '081234567896',
                'bio' => 'Mahasiswa Desain Komunikasi Visual semester 5. Ahli dalam motion graphic dan video editing.',
                'linkedin' => 'https://linkedin.com/in/gilangramadhan',
                'instagram' => 'gilangramadhan',
                'github' => 'gilangramadhan',
            ],
            [
                'name' => 'Putri Wulandari',
                'division_id' => $divMultimedia,
                'position_id' => $wakilKetuaDivisi,
                'nim' => '20240008',
                'email' => 'putri.wulandari@csa.ac.id',
                'phone' => '081234567897',
                'bio' => 'Mahasiswa Desain Grafis semester 3. Spesialis ilustrasi dan branding visual.',
                'instagram' => 'putriwulandari',
            ],
            [
                'name' => 'Dimas Ardiansyah',
                'division_id' => $divMultimedia,
                'position_id' => $anggota,
                'nim' => '20240009',
                'email' => 'dimas.ardiansyah@csa.ac.id',
                'bio' => 'Mahasiswa Fotografi semester 3. Berpengalaman dalam fotografi event dan editing.',
                'instagram' => 'dimasardiansyah',
                'is_active' => false,
            ],
            [
                'name' => 'Lina Marlina',
                'division_id' => $divMultimedia,
                'position_id' => $anggota,
                'nim' => '20240012',
                'email' => 'lina.marlina@csa.ac.id',
                'bio' => 'Mahasiswa Animasi semester 1. Sedang belajar 2D animation dan character design.',
                'instagram' => 'linamarlina',
            ],
            [
                'name' => 'Hendra Gunawan',
                'division_id' => $divMultimedia,
                'position_id' => $anggota,
                'nim' => '20240016',
                'email' => 'hendra.gunawan@csa.ac.id',
                'bio' => 'Mahasiswa Desain Komunikasi Visual semester 1. Tertarik pada UI design dan prototyping.',
                'instagram' => 'hendragunawan',
            ],
        ];

        foreach ($members as $member) {
            Member::create(array_merge([
                'is_active' => true,
                'slug' => Str::slug($member['name']),
            ], $member));
        }

        $this->command->info('MemberSeeder berhasil: ' . count($members) . ' anggota dibuat.');
    }
}
