<?php

namespace App\Enums;

enum Komisariat: string
{
    case AlFarabi = 'al_farabi';
    case Leviathan = 'leviathan';
    case Kuf = 'kuf';
    case AlKindi = 'al_kindi';
    case IbnuRusyd = 'ibnu_rusyd';
    case Febi = 'febi';
    case Avempace = 'avempace';
    case Avenzoar = 'Avenzoar';
    case PsikologiDanKesehatan = 'psikologi_dan_kesehatan';

    public function label(): string
    {
        return match ($this) {
            self::AlFarabi => 'IMM Al Farabi',
            self::Leviathan => 'IMM Leviathan',
            self::Kuf => 'IMM KUF',
            self::AlKindi => 'IMM Al Kindi',
            self::IbnuRusyd => 'IMM Ibnu Rusyd',
            self::Febi => 'IMM FEBI',
            self::Avempace => 'IMM Avempace',
            self::Avenzoar => 'IMM Avenzoar',
            self::PsikologiDanKesehatan => 'IMM Psikologi dan Kesehatan',
        };
    }
}
