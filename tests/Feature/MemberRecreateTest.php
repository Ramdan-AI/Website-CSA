<?php

namespace Tests\Feature;

use App\Models\Division;
use App\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberRecreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_deleted_member_frees_email_and_nim(): void
    {
        $division = Division::create(['name' => 'Divisi Uji', 'slug' => 'divisi-uji', 'is_active' => true]);

        $member = Member::create([
            'name' => 'maba 3',
            'division_id' => $division->id,
            'email' => 'maba3@example.test',
            'nim' => '20240001',
            'is_active' => true,
        ]);

        $member->delete();

        $trashed = Member::withTrashed()->find($member->id);
        $this->assertNull($trashed->email);
        $this->assertNotSame('20240001', $trashed->nim);
    }

    public function test_member_can_be_created_again_with_same_email_after_delete(): void
    {
        $division = Division::create(['name' => 'Divisi Uji', 'slug' => 'divisi-uji', 'is_active' => true]);

        $first = Member::create([
            'name' => 'maba 3',
            'division_id' => $division->id,
            'email' => 'maba3@example.test',
            'nim' => '20240001',
            'is_active' => true,
        ]);

        $first->delete();

        $second = Member::create([
            'name' => 'maba 3',
            'division_id' => $division->id,
            'email' => 'maba3@example.test',
            'nim' => '20240001',
            'is_active' => true,
        ]);

        $this->assertNotNull($second->id);
        $this->assertSame('maba-3-1', $second->slug);
    }
}
