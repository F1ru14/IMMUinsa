<?php

namespace App\Enums;

enum FocusArea: string
{
    case Entrepreneur = 'entrepreneur';
    case Politisi = 'politisi';
    case Akademisi = 'akademisi';
    case PengabdiPersyarikatan = 'pengabdi_persyarikatan';

    public function label(): string
    {
        return match ($this) {
            self::Entrepreneur => 'Entrepreneur',
            self::Politisi => 'Politisi & Kebijakan Publik',
            self::Akademisi => 'Akademisi & Peneliti',
            self::PengabdiPersyarikatan => 'Pengabdi Persyarikatan',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::Entrepreneur => 'Pengembangan bisnis, rintisan usaha mandiri, inovasi ekonomi kreatif, dan filantropi.',
            self::Politisi => 'Kepemimpinan publik, analisis kebijakan, advokasi sosial politik, dan keparlemenan.',
            self::Akademisi => 'Pendidikan tinggi, penelitian ilmiah, publikasi jurnal, dan pengembangan sains keilmuan.',
            self::PengabdiPersyarikatan => 'Penguatan pimpinan Muhammadiyah/Aisyiyah, dakwah transformatif, dan amal usaha.',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Entrepreneur => 'bg-amber-100 text-amber-800 border-amber-300',
            self::Politisi => 'bg-blue-100 text-blue-800 border-blue-300',
            self::Akademisi => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            self::PengabdiPersyarikatan => 'bg-red-100 text-red-800 border-red-300',
        };
    }
}
