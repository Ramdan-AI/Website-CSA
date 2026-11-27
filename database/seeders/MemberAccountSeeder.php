<?php

namespace Database\Seeders;

use App\Models\Division;
use App\Models\Member;
use App\Models\Position;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\Support\SeedPassword;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

abstract class MemberAccountSeeder extends Seeder
{
    protected const PEMROGRAMAN = 'Divisi Pemrograman';

    protected const MULTIMEDIA = 'Divisi Multimedia';

    private const SEMESTER_BY_ENTRY_YEAR = ['23' => 7, '24' => 5, '25' => 3];

    abstract protected function roleSlug(): string;

    abstract protected function people(): array;

    public function run(): void
    {
        $divisions = Division::all()->keyBy('name');
        $positions = Position::all()->keyBy('name');

        if ($divisions->isEmpty() || $positions->isEmpty()) {
            $this->command?->warn('Pastikan DivisionSeeder dan PositionSeeder sudah dijalankan.');

            return;
        }

        $roleId = Role::where('slug', $this->roleSlug())->firstOrFail()->id;
        $password = SeedPassword::hashed();

        foreach ($this->people() as [$nim, $name, $positionName, $divisionName]) {
            $email = $this->emailFor($name);

            $user = User::create([
                'role_id' => $roleId,
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'is_active' => true,
            ]);

            $this->createMember($user, $nim, $positions[$positionName], $divisions[$divisionName]);
        }
    }

    private function createMember(User $user, string $nim, Position $position, Division $division): void
    {
        $semester = self::SEMESTER_BY_ENTRY_YEAR[substr($nim, 0, 2)];

        Member::create([
            'user_id' => $user->id,
            'division_id' => $division->id,
            'position_id' => $position->id,
            'name' => $user->name,
            'slug' => Str::slug($user->name),
            'nim' => $nim,
            'semester' => $semester,
            'level' => $division->has_levels ? $this->levelFor($semester) : null,
            'email' => $user->email,
            'is_active' => true,
        ]);
    }

    private function emailFor(string $name): string
    {
        $words = preg_split('/\s+/', trim($name));

        return strtolower(implode('', array_slice($words, 0, 2))).'@gmail.com';
    }

    private function levelFor(int $semester): int
    {
        return match (true) {
            $semester <= 2 => 1,
            $semester <= 4 => 2,
            default => 3,
        };
    }
}
