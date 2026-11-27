<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\SeedsOrganization;
use Tests\TestCase;

class OrganizationChartTest extends TestCase
{
    use RefreshDatabase;
    use SeedsOrganization;

    protected function setUp(): void
    {
        parent::setUp();

        Setting::current();
    }

    public function test_landing_page_shows_the_full_structure_in_order(): void
    {
        $this->seedOrganization();

        $this->get('/')->assertOk()->assertSeeTextInOrder([
            'Pelindung', 'Penasihat', 'Pembina', 'Ahfi Fauka Pranata', 'NIDN 0409129402',
            'Ketua Umum', 'Nida Mutmainah', 'NIM 23110210',
            'Wakil Ketua Umum', 'Cellomita Rahdianti Putri',
            'Sekretaris I', 'Helmi Fauzan Ramdani', 'Sekretaris II', 'Giestiani Putri',
            'Bendahara I', 'Lenny Juwita Silalahi', 'Bendahara II', 'Nazwa Nabila Putri',
            'Humas I', 'Salma Saepul Milah', 'Humas II', 'Tita Fitriani',
            'Litbang I', 'Hanifa Febriana Fadillah', 'Litbang II', 'Nisrina Nur Azizah',
            'Litbang III', 'Iasha Tsamrotul Fuadi',
            'Ketua Pemrograman', 'Muhammad Ramdan', 'Wakil Ketua Pemrograman', 'Rozan Nouval',
            'Koordinator Proyek Pemrograman', 'Tegar Dinar Harsya Ibrahim',
            'Wakil Koordinator Proyek Pemrograman', 'Alief Varied Fattarohman',
            'Ketua Koordinator Proyek Multimedia', 'Muhamad Riski Riyadyansyah',
            'Ketua Multimedia', 'Asep Farhan Pratama', 'Wakil Ketua Multimedia', 'Fikri Maulana Abdillah',
            'Koordinator Tim Kreatif', 'Mima Rahma Putri', 'Naila Nawar Haura',
            'Tim Kreatif', 'Fipi Asiah Rosdiani', 'Muhammad Rizal Fariansyah',
            'Tengku Muhamad Azi Juniar', 'Andika Trigunadi Putra',
            'Seluruh Anggota CSA',
        ])->assertDontSeeText('Belum diisi');
    }

    public function test_landing_page_profiles_the_current_advisor(): void
    {
        $this->get('/')->assertOk()
            ->assertSeeTextInOrder([
                'Pembina', 'Ahfi Fauka Pranata', 'S2 Manajemen',
                'Call Center System Engineer',
            ])
            ->assertSee('images/pembina-ahfi-fauka-pranata.webp')
            ->assertDontSeeText('Software Engineer')
            ->assertDontSee('Adi Raharjo')
            ->assertDontSee('S.Kom., M.M.');
    }

    public function test_vacant_positions_stay_visible_in_the_structure(): void
    {
        Member::create(['name' => 'Anggota Tunggal', 'nim' => '24000001', 'is_active' => true]);

        $this->get('/')->assertOk()->assertSeeTextInOrder([
            'Ketua Umum', 'Belum diisi', 'Seluruh Anggota CSA',
        ]);
    }
}
