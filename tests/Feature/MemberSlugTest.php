<?php

namespace Tests\Feature;

use App\Models\Division;
use App\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberSlugTest extends TestCase
{
    use RefreshDatabase;

    public function test_recreating_deleted_member_name_generates_unique_slug(): void
    {
        $division = Division::create(['name' => 'Divisi Uji', 'slug' => 'divisi-uji', 'is_active' => true]);

        $first = Member::create([
            'name' => 'maba 3',
            'division_id' => $division->id,
            'nim' => '20240001',
            'email' => 'maba3@example.test',
            'is_active' => true,
        ]);

        $this->assertSame('maba-3', $first->slug);

        // Hapus (soft delete) lalu buat ulang dengan nama sama.
        $first->delete();

        $second = Member::create([
            'name' => 'maba 3',
            'division_id' => $division->id,
            'nim' => '20240002',
            'email' => 'maba3b@example.test',
            'is_active' => true,
        ]);

        $this->assertSame('maba-3-1', $second->slug);
        $this->assertSame(2, Member::withTrashed()->where('slug', 'like', 'maba-3%')->count());
    }
}
