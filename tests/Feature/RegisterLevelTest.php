<?php

namespace Tests\Feature;

use App\Models\Division;
use App\Models\Member;
use App\Models\Role;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterLevelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Buka pendaftaran (mode manual) agar route /register dapat diakses.
        Setting::create([
            'site_name' => 'CSA',
            'registration_open' => true,
            'registration_use_schedule' => false,
        ]);
    }

    private function division(string $name, bool $hasLevels): Division
    {
        return Division::create([
            'name' => $name,
            'slug' => str()->slug($name),
            'has_levels' => $hasLevels,
            'is_active' => true,
        ]);
    }

    public function test_register_page_shows_levels_for_leveled_division(): void
    {
        Role::create(['name' => 'Anggota', 'slug' => 'anggota', 'description' => null]);
        $this->division('Divisi Pemrograman', true);

        $response = $this->get('/register');
        $response->assertOk();
        $response->assertSee('Tingkatan');
        $response->assertSee('Tingkat 1');
        $response->assertSee('Pemula');
    }

    public function test_level_is_required_for_leveled_division(): void
    {
        Role::create(['name' => 'Anggota', 'slug' => 'anggota', 'description' => null]);
        $division = $this->division('Divisi Pemrograman', true);

        $response = $this->post('/register', [
            'name' => 'Budi',
            'email' => 'budi@example.test',
            'nim' => '20240001',
            'semester' => 1,
            'whatsapp' => '081234567890',
            'division_id' => $division->id,
            'password' => 'password123',
            'password_confirmation' => 'password123',
            // level sengaja tidak diisi
        ]);

        $response->assertSessionHasErrors('level');
        $this->assertSame(0, Member::count());
    }

    public function test_level_is_saved_for_leveled_division(): void
    {
        Role::create(['name' => 'Anggota', 'slug' => 'anggota', 'description' => null]);
        $division = $this->division('Divisi Pemrograman', true);

        $this->post('/register', [
            'name' => 'Budi',
            'email' => 'budi@example.test',
            'nim' => '20240001',
            'semester' => 1,
            'level' => 2,
            'whatsapp' => '081234567890',
            'division_id' => $division->id,
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect();

        $member = Member::where('nim', '20240001')->first();
        $this->assertNotNull($member);
        $this->assertSame(2, $member->level);
    }

    public function test_level_ignored_for_non_leveled_division(): void
    {
        Role::create(['name' => 'Anggota', 'slug' => 'anggota', 'description' => null]);
        $division = $this->division('Divisi Multimedia', false);

        $this->post('/register', [
            'name' => 'Ani',
            'email' => 'ani@example.test',
            'nim' => '20240002',
            'semester' => 1,
            'whatsapp' => '081234567891',
            'division_id' => $division->id,
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect();

        $this->assertNull(Member::where('nim', '20240002')->first()->level);
    }
}
