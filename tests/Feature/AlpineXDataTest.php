<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Mencegah regresi: komentar "//" di dalam atribut x-data akan
 * meng-Comment sisa ekspresi setelah HTML menormalkan baris baru,
 * sehingga method seperti markSeen() menjadi "not defined" di Alpine.
 */
class AlpineXDataTest extends TestCase
{
    /**
     * Tidak boleh ada komentar "//" di dalam nilai x-data mana pun.
     */
    public function test_no_line_comments_inside_x_data_attributes(): void
    {
        $views = $this->viewFiles();
        $this->assertNotEmpty($views, 'Tidak menemukan file blade untuk diperiksa.');

        $offenders = [];

        foreach ($views as $path) {
            $contents = file_get_contents($path);

            // Ambil semua nilai atribut x-data="..." (dukung multi-baris).
            if (preg_match_all('/x-data="([^"]*)"/s', $contents, $matches)) {
                foreach ($matches[1] as $expression) {
                    if (str_contains($expression, '//')) {
                        $offenders[] = str_replace(base_path().DIRECTORY_SEPARATOR, '', $path);
                    }
                }
            }
        }

        $this->assertSame(
            [],
            array_values(array_unique($offenders)),
            'Atribut x-data tidak boleh memakai komentar "//": '.implode(', ', array_unique($offenders))
        );
    }

    /**
     * @return array<int, string>
     */
    private function viewFiles(): array
    {
        $dir = resource_path('views');

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS)
        );

        $files = [];

        foreach ($iterator as $file) {
            if ($file->isFile() && str_ends_with($file->getFilename(), '.blade.php')) {
                $files[] = $file->getPathname();
            }
        }

        return $files;
    }
}
