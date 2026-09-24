<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tingkatan & semester anggota, serta penanda divisi bertingkat.
     *
     * Tingkatan bersifat eksplisit (bukan turunan semester), karena skill
     * anggota tidak selalu sejalan dengan semester. Hanya divisi bertingkat
     * (mis. Pemrograman) yang mengisi `level`.
     */
    public function up(): void
    {
        Schema::table('divisions', function (Blueprint $table) {
            $table->boolean('has_levels')->default(false)->after('skills');
        });

        Schema::table('members', function (Blueprint $table) {
            $table->unsignedTinyInteger('semester')->nullable()->after('nim');
            $table->unsignedTinyInteger('level')->nullable()->after('semester');
        });

        // NIM harus unik. Bersihkan duplikat lebih dulu (simpan id terkecil)
        // agar indeks dapat dibuat tanpa gagal pada data lama.
        $duplicates = DB::table('members')
            ->select('nim')
            ->whereNotNull('nim')
            ->where('nim', '!=', '')
            ->groupBy('nim')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('nim');

        foreach ($duplicates as $nim) {
            $keepId = DB::table('members')->where('nim', $nim)->min('id');

            DB::table('members')
                ->where('nim', $nim)
                ->where('id', '!=', $keepId)
                ->update(['nim' => DB::raw("CONCAT(nim, '-', id)")]);
        }

        Schema::table('members', function (Blueprint $table) {
            $table->unique('nim', 'members_nim_unique');
        });
    }

    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropUnique('members_nim_unique');
            $table->dropColumn(['semester', 'level']);
        });

        Schema::table('divisions', function (Blueprint $table) {
            $table->dropColumn('has_levels');
        });
    }
};
