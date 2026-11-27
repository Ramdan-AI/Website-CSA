<?php

namespace Database\Seeders;

class AnggotaSeeder extends MemberAccountSeeder
{
    protected function roleSlug(): string
    {
        return 'anggota';
    }

    protected function people(): array
    {
        return [
            ['23110241', 'Salma Saepul Milah', 'Humas', self::MULTIMEDIA],
            ['24110204', 'Tita Fitriani', 'Humas', self::MULTIMEDIA],
            ['24110029', 'Hanifa Febriana Fadillah', 'Litbang', self::PEMROGRAMAN],
            ['24110202', 'Nisrina Nur Azizah', 'Litbang', self::PEMROGRAMAN],
            ['24030066', 'Iasha Tsamrotul Fuadi', 'Litbang', self::MULTIMEDIA],
            ['23110189', 'Tegar Dinar Harsya Ibrahim', 'Koordinator Proyek', self::PEMROGRAMAN],
            ['24110152', 'Alief Varied Fattarohman', 'Wakil Koordinator Proyek', self::PEMROGRAMAN],
            ['23110275', 'Muhamad Riski Riyadyansyah', 'Koordinator Proyek', self::MULTIMEDIA],
            ['24110074', 'Mima Rahma Putri', 'Koordinator Tim Kreatif', self::MULTIMEDIA],
            ['24110002', 'Naila Nawar Haura', 'Koordinator Tim Kreatif', self::MULTIMEDIA],
            ['24110203', 'Fipi Asiah Rosdiani', 'Tim Kreatif', self::MULTIMEDIA],
            ['24110101', 'Muhammad Rizal Fariansyah', 'Tim Kreatif', self::MULTIMEDIA],
            ['251110204', 'Tengku Muhamad Azi Juniar', 'Tim Kreatif', self::MULTIMEDIA],
            ['24110041', 'Andika Trigunadi Putra', 'Tim Kreatif', self::MULTIMEDIA],
            ['24020008', 'Novita Nur Fitriani', 'Anggota', self::PEMROGRAMAN],
            ['24110043', 'Jessy Budiane', 'Anggota', self::PEMROGRAMAN],
            ['24110042', 'Deaz Hizbiyah Rahim', 'Anggota', self::PEMROGRAMAN],
            ['24110077', 'Vira Riska Nuraziza', 'Anggota', self::PEMROGRAMAN],
            ['251110206', 'Valdiaz Gahari', 'Anggota', self::PEMROGRAMAN],
            ['24110185', 'Intan Agustin', 'Anggota', self::PEMROGRAMAN],
            ['251110061', 'Alya Marsya Salsabila', 'Anggota', self::PEMROGRAMAN],
            ['251110160', 'Hafidza Ramadhani', 'Anggota', self::PEMROGRAMAN],
            ['251110150', 'Dela Anggita', 'Anggota', self::PEMROGRAMAN],
            ['251110180', 'Sheihkah Fauziah', 'Anggota', self::PEMROGRAMAN],
            ['24110051', 'Arif Fatah Budiman', 'Anggota', self::PEMROGRAMAN],
            ['24110205', 'Fanny Dwi Septiani', 'Anggota', self::PEMROGRAMAN],
            ['251110177', 'Safira Nadiatul Fauzia', 'Anggota', self::PEMROGRAMAN],
            ['24110092', 'Aliya Himatul Aliyah', 'Anggota', self::PEMROGRAMAN],
            ['24030007', 'Jauza Kamila Hadi', 'Anggota', self::PEMROGRAMAN],
            ['24110112', 'Dila Nurfadilah', 'Anggota', self::PEMROGRAMAN],
            ['24110146', 'Adina Kanesya Dahuri', 'Anggota', self::PEMROGRAMAN],
            ['24110207', 'Fitria Oktaviani', 'Anggota', self::MULTIMEDIA],
        ];
    }
}
