<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ErrorPagesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Route::get('/_abort/{status}', fn (int $status) => abort($status));
    }

    public static function customErrorPages(): array
    {
        return [
            'unauthenticated' => [401, 'Perlu masuk'],
            'forbidden' => [403, 'Akses ditolak'],
            'not found' => [404, 'Halaman tidak ditemukan'],
            'page expired' => [419, 'Halaman kedaluwarsa'],
            'too many requests' => [429, 'Terlalu banyak permintaan'],
            'server error' => [500, 'Terjadi kesalahan'],
            'maintenance' => [503, 'Sedang pemeliharaan'],
        ];
    }

    #[DataProvider('customErrorPages')]
    public function test_status_renders_custom_page(int $status, string $title): void
    {
        $this->get("/_abort/{$status}")
            ->assertStatus($status)
            ->assertSee($title)
            ->assertSee('images/csa-logo.webp', false)
            ->assertSee('error-page', false);
    }

    public function test_page_expired_offers_to_reload_the_form(): void
    {
        $this->get('/_abort/419')->assertSee('Muat ulang formulir');
    }
}
