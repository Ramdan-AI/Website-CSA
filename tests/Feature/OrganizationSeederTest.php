<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\SuperAdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use RuntimeException;
use Tests\Support\SeedsOrganization;
use Tests\TestCase;

class OrganizationSeederTest extends TestCase
{
    use RefreshDatabase;
    use SeedsOrganization;

    public function test_accounts_are_spread_over_the_existing_roles_only(): void
    {
        $this->seedOrganization();

        $this->assertSame([
            'super-admin' => 2,
            'ketua' => 1,
            'wakil-ketua' => 1,
            'ketua-divisi' => 2,
            'wakil-ketua-divisi' => 2,
            'sekretaris' => 2,
            'bendahara' => 2,
            'anggota' => 32,
        ], Role::withCount('users')->orderBy('id')->pluck('users_count', 'slug')->all());
        $this->assertSame(42, Member::count());
    }

    public function test_super_admins_can_be_added_again_without_duplicates(): void
    {
        $this->seedOrganization();

        $this->seed(SuperAdminSeeder::class);

        $this->assertSame(
            ['admin@csa.local', 'ahfifauka@gmail.com'],
            Role::where('slug', 'super-admin')->firstOrFail()->users()->orderBy('email')->pluck('email')->all()
        );
    }

    public function test_every_account_uses_the_configured_seed_password(): void
    {
        $this->seedOrganization();

        $this->assertTrue(User::all()->every(fn (User $user) => Hash::check(self::SEED_PASSWORD, $user->password)));
    }

    public function test_officers_without_their_own_role_keep_their_position(): void
    {
        $this->seedOrganization();

        $member = Member::with(['position', 'user.role', 'division'])->where('nim', '23110189')->firstOrFail();

        $this->assertSame('Tegar Dinar Harsya Ibrahim', $member->name);
        $this->assertSame('Koordinator Proyek', $member->position->name);
        $this->assertSame('anggota', $member->user->role->slug);
        $this->assertSame('Divisi Pemrograman', $member->division->name);
        $this->assertSame(7, $member->semester);
        $this->assertSame('tegardinar@gmail.com', $member->email);
    }

    public function test_account_seeding_is_refused_without_a_password(): void
    {
        Role::create(['name' => 'Super Admin', 'slug' => 'super-admin']);
        config(['seeding.password' => null]);

        $this->expectException(RuntimeException::class);

        $this->seed(SuperAdminSeeder::class);
    }
}
