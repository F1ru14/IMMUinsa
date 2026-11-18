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
}
