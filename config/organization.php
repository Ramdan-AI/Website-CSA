<?php

$advisor = [
    'name' => 'Ahfi Fauka Pranata',
    'identifier' => 'NIDN 0409129402',
    'photo' => 'images/pembina-ahfi-fauka-pranata.webp',
    'about' => [
        'Ahfi Fauka Pranata adalah pembina Creative Student Association, organisasi mahasiswa STMIK Mardira Indonesia tempat anggotanya belajar pemrograman dan multimedia lewat proyek nyata.',
        'Sebagai pembina, beliau mendampingi pengurus dan anggota dalam menjalankan kegiatan organisasi.',
    ],
    'education' => 'S2 Manajemen',
    'experiences' => [
        'Call Center System Engineer' => '8 tahun',
    ],
];

return [
    'advisor' => $advisor,
    'advisors' => [
        [
            'title' => 'Pelindung',
            'people' => [
                ['name' => 'Ketua STMIK Mardira Indonesia', 'identifier' => null],
            ],
        ],
        [
            'title' => 'Penasihat',
            'people' => [
                ['name' => 'Wakil Ketua Bidang Kemahasiswaan dan Alumni STMIK Mardira Indonesia', 'identifier' => null],
            ],
        ],
        [
            'title' => 'Pembina',
            'people' => [
                ['name' => $advisor['name'], 'identifier' => $advisor['identifier']],
            ],
        ],
    ],
];
