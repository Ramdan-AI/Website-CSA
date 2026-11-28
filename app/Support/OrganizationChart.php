<?php

namespace App\Support;

use Illuminate\Support\Collection;

class OrganizationChart
{
    private const RANKS = ['I', 'II', 'III', 'IV', 'V'];

    private const PEMROGRAMAN = 'Divisi Pemrograman';

    private const MULTIMEDIA = 'Divisi Multimedia';

    private Collection $members;

    public function __construct(Collection $members)
    {
        $this->members = $members->sortBy('id');
    }

    public function leadership(): array
    {
        return [
            $this->box('Ketua Umum', $this->holders('Ketua')),
            $this->box('Wakil Ketua Umum', $this->holders('Wakil Ketua')),
        ];
    }

    public function officers(): array
    {
        return array_map(
            fn (string $position) => $this->ranked($position),
            ['Sekretaris', 'Bendahara', 'Humas', 'Litbang'],
        );
    }

    public function divisions(): array
    {
        return [
            [
                $this->box('Ketua Pemrograman', $this->holders('Ketua Divisi', self::PEMROGRAMAN)),
                $this->box('Wakil Ketua Pemrograman', $this->holders('Wakil Ketua Divisi', self::PEMROGRAMAN)),
            ],
            [
                $this->box('Koordinator Proyek Pemrograman', $this->holders('Koordinator Proyek', self::PEMROGRAMAN)),
                $this->box('Wakil Koordinator Proyek Pemrograman', $this->holders('Wakil Koordinator Proyek', self::PEMROGRAMAN)),
            ],
            [
                $this->box('Ketua Koordinator Proyek Multimedia', $this->holders('Koordinator Proyek', self::MULTIMEDIA)),
                ...$this->optionalBox('Wakil Koordinator Proyek Multimedia', $this->holders('Wakil Koordinator Proyek', self::MULTIMEDIA)),
            ],
            [
                $this->box('Ketua Multimedia', $this->holders('Ketua Divisi', self::MULTIMEDIA)),
                $this->box('Wakil Ketua Multimedia', $this->holders('Wakil Ketua Divisi', self::MULTIMEDIA)),
            ],
        ];
    }

    public function creativeTeam(): array
    {
        return [
            $this->box('Koordinator Tim Kreatif', $this->holders('Koordinator Tim Kreatif')),
            $this->box('Tim Kreatif', $this->holders('Tim Kreatif')),
        ];
    }

    private function ranked(string $position): array
    {
        $holders = $this->holders($position);

        if ($holders->count() < 2) {
            return [$this->box($position, $holders)];
        }

        return $holders
            ->map(fn ($member, int $index) => $this->box(
                $position.' '.(self::RANKS[$index] ?? $index + 1),
                collect([$member]),
            ))
            ->all();
    }

    private function holders(string $position, ?string $division = null): Collection
    {
        return $this->members
            ->filter(fn ($member) => $member->position?->name === $position
                && ($division === null || $member->division?->name === $division))
            ->values();
    }

    private function optionalBox(string $title, Collection $holders): array
    {
        return $holders->isEmpty() ? [] : [$this->box($title, $holders)];
    }

    private function box(string $title, Collection $holders): array
    {
        return [
            'title' => $title,
            'people' => $holders
                ->map(fn ($member) => ['name' => $member->name, 'identifier' => 'NIM '.$member->nim])
                ->all(),
        ];
    }
}
