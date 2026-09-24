<?php

namespace Database\Seeders;

use App\Models\Division;
use App\Models\Member;
use App\Models\Position;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MemberSeeder extends Seeder
{
    /**
     * Password awal seluruh akun anggota. Anggota diharapkan menggantinya
     * sendiri setelah login pertama.
     */
    private const DEFAULT_PASSWORD = '12345678';

    /**
     * Awalan NIM placeholder. Ganti dengan NIM asli bila sudah tersedia.
     */
    private const NIM_PREFIX = '2026';

    public function run(): void
    {
        $divisions = Division::all();
        $positions = Position::all()->keyBy('name');

        if ($divisions->isEmpty() || $positions->isEmpty()) {
            $this->command->warn('Pastikan DivisionSeeder dan PositionSeeder sudah dijalankan.');

            return;
        }

        $divPemrograman = $divisions->firstWhere('name', 'Divisi Pemrograman');
        $divMultimedia = $divisions->firstWhere('name', 'Divisi Multimedia');

        // [nama, nama jabatan, divisi, semester|null]
        $members = [
            // Pengurus inti (lintas divisi).
            ['Nida Mutmainah', 'Ketua', null, 7],
            ['Cellomita Rahdianti', 'Wakil Ketua', null, 7],
            ['Vira Riska', 'Bendahara', null, 5],
            ['Safira Nadiatul Fauzia', 'Bendahara', null, 3],
            ['Helmi Fauzan Ramdani', 'Sekretaris', $divPemrograman, 5],
            ['Giestiani Putri', 'Sekretaris', $divPemrograman, 3],
            ['Muhammad Ramdan', 'Ketua Divisi', $divPemrograman, 7],
            ['Rozan Nouval', 'Wakil Ketua Divisi', $divPemrograman, 3],
            ['Asep Farhan', 'Ketua Divisi', $divMultimedia, 7],
            ['Fikri Maulana Abdillah', 'Wakil Ketua Divisi', $divMultimedia, 5],

            // Anggota Divisi Pemrograman.
            ['Sheihkah Fauziah', 'Anggota', $divPemrograman, 3],
            ['Klara Oktapia Putri', 'Anggota', $divPemrograman, 5],
            ['Valdiaz Gahari', 'Anggota', $divPemrograman, 3],
            ['Fadhlan Aqil Syafiq', 'Anggota', $divPemrograman, 3],
            ['Nazwa Nabila Putri', 'Anggota', $divPemrograman, 3],
            ['Dela Anggita', 'Anggota', $divPemrograman, 3],
            ['Hafidza Ramadhani', 'Anggota', $divPemrograman, 3],
            ['Keisha Aurelia Hermawan', 'Anggota', $divPemrograman, 5],
            ['Shofura Ratna', 'Anggota', $divPemrograman, 5],
            ['Nisrina Nur Azizah', 'Anggota', $divPemrograman, 5],
            ['Fanny Dwi Septiani', 'Anggota', $divPemrograman, 5],
            ['Intan Agustin', 'Anggota', $divPemrograman, 5],
            ['Risman Nurrizki', 'Anggota', $divPemrograman, 5],
            ['Arif Fatah Budiman', 'Anggota', $divPemrograman, 5],
            ['Jauza Kamila Hadi', 'Anggota', $divPemrograman, 5],
            ['Dila Nurfadilah', 'Anggota', $divPemrograman, 5],
            ['Lenny Juwita Silalahi', 'Anggota', $divPemrograman, 3],
            ['Adina Kanesya Dahuri', 'Anggota', $divPemrograman, 5],
            ['Aliya Himatul Aliyah', 'Anggota', $divPemrograman, 5],

            // Anggota Divisi Multimedia.
            ['Fitria Oktaviani', 'Anggota', $divMultimedia, 5],
            ['Muhammad Rizal Fariansyah', 'Anggota', $divMultimedia, 5],
            ['Moch Raihan Hari Pratama', 'Anggota', $divMultimedia, 5],
            ['Fipi Asiah Rosdiani', 'Anggota', $divMultimedia, 5],
            ['Tengku Muhamad Azi Juniar', 'Anggota', $divMultimedia, 3],
            ['Iasha Tsamrotul Fuadi', 'Anggota', $divMultimedia, 5],
            ['Tita Fitriani', 'Anggota', $divMultimedia, 5],
            ['Deden Falah Ihsan Kamil', 'Anggota', $divMultimedia, 5],
            ['Andika Trigunadi Putra', 'Anggota', $divMultimedia, 5],
        ];

        $password = Hash::make(self::DEFAULT_PASSWORD);
        $sequence = 0;

        foreach ($members as [$name, $positionName, $division, $semester]) {
            $position = $positions->get($positionName);
            $role = $this->resolveRole($positionName);

            $email = $this->emailFor($name);
            $nim = self::NIM_PREFIX.str_pad((string) (++$sequence), 4, '0', STR_PAD_LEFT);

            $user = User::create([
                'role_id' => $role?->id,
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'is_active' => true,
            ]);

            Member::create([
                'user_id' => $user->id,
                'division_id' => $division?->id,
                'position_id' => $position?->id,
                'name' => $name,
                'slug' => Str::slug($name),
                'nim' => $nim,
                'semester' => $semester,
                'level' => $this->resolveLevel($division, $semester),
                'email' => $email,
                'is_active' => true,
            ]);
        }

        $this->command->info('MemberSeeder berhasil: '.count($members).' anggota dan akun login dibuat.');
    }

    /**
     * Email dibuat dari dua kata pertama nama, tanpa spasi, ditambah @gmail.com.
     * Contoh: "Muhammad Ramdan" -> "muhammadramdan@gmail.com".
     */
    private function emailFor(string $name): string
    {
        $words = preg_split('/\s+/', trim($name));
        $local = strtolower(implode('', array_slice($words, 0, 2)));

        return $local.'@gmail.com';
    }

    /**
     * Level hanya berlaku untuk divisi bertingkat. Diturunkan dari semester:
     * <=2 -> 1 (Pemula), <=4 -> 2 (Menengah), sisanya -> 3 (Expert).
     */
    private function resolveLevel(?Division $division, ?int $semester): ?int
    {
        if (! $division?->has_levels || $semester === null) {
            return null;
        }

        return $semester <= 2 ? 1 : ($semester <= 4 ? 2 : 3);
    }

    /**
     * Petakan nama jabatan ke role, sama seperti logika MemberController.
     */
    private function resolveRole(?string $positionName): ?Role
    {
        $name = strtolower($positionName ?? '');

        $slug = match (true) {
            str_contains($name, 'wakil ketua divisi') => 'wakil-ketua-divisi',
            str_contains($name, 'ketua divisi') => 'ketua-divisi',
            str_contains($name, 'wakil ketua') => 'wakil-ketua',
            str_contains($name, 'ketua') => 'ketua',
            str_contains($name, 'sekretaris') => 'sekretaris',
            str_contains($name, 'bendahara') => 'bendahara',
            default => 'anggota',
        };

        return Role::where('slug', $slug)->first();
    }
}
