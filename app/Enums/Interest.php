<?php

namespace App\Enums;

enum Interest: string
{
    // Entrepreneur
    case UsahaDanUmkm = 'usaha_dan_umkm';
    case StartupDanTeknologi = 'startup_dan_teknologi';
    case KeuanganDanInvestasi = 'keuangan_dan_investasi';
    case EkonomiKreatif = 'ekonomi_kreatif';
    case KewirausahaanSosial = 'kewirausahaan_sosial';

    // Politisi
    case KebijakanPublik = 'kebijakan_publik';
    case AdvokasiDanHukum = 'advokasi_dan_hukum';
    case PolitikDanKeparlemenan = 'politik_dan_keparlemenan';
    case PemerintahanDanBirokrasi = 'pemerintahan_dan_birokrasi';
    case AnalisisSosialPolitik = 'analisis_sosial_politik';

    // Akademisi
    case RisetDanPublikasi = 'riset_dan_publikasi';
    case PendidikanDanPengajaran = 'pendidikan_dan_pengajaran';
    case SainsDanTeknologi = 'sains_dan_teknologi';
    case BeasiswaDanStudiLanjut = 'beasiswa_dan_studi_lanjut';
    case LiterasiDanPenulisan = 'literasi_dan_penulisan';

    // Pengabdi Persyarikatan
    case DakwahDanKajianIslam = 'dakwah_dan_kajian_islam';
    case OrganisasiMuhammadiyah = 'organisasi_muhammadiyah';
    case KegiatanSosialDanRelawan = 'kegiatan_sosial_dan_relawan';
    case AmalUsahaDanPemberdayaan = 'amal_usaha_dan_pemberdayaan';
    case KepemimpinanDanManajemen = 'kepemimpinan_dan_manajemen';

    public function label(): string
    {
        return match ($this) {
            self::UsahaDanUmkm => 'Usaha & UMKM',
            self::StartupDanTeknologi => 'Startup & Teknologi Digital',
            self::KeuanganDanInvestasi => 'Keuangan & Investasi',
            self::EkonomiKreatif => 'Ekonomi Kreatif',
            self::KewirausahaanSosial => 'Kewirausahaan Sosial & Filantropi',
            self::KebijakanPublik => 'Kebijakan Publik',
            self::AdvokasiDanHukum => 'Advokasi & Hukum',
            self::PolitikDanKeparlemenan => 'Politik & Keparlemenan',
            self::PemerintahanDanBirokrasi => 'Pemerintahan & Birokrasi',
            self::AnalisisSosialPolitik => 'Analisis Sosial Politik',
            self::RisetDanPublikasi => 'Riset & Publikasi Ilmiah',
            self::PendidikanDanPengajaran => 'Pendidikan & Pengajaran',
            self::SainsDanTeknologi => 'Sains & Teknologi',
            self::BeasiswaDanStudiLanjut => 'Beasiswa & Studi Lanjut',
            self::LiterasiDanPenulisan => 'Literasi & Penulisan',
            self::DakwahDanKajianIslam => 'Dakwah & Kajian Islam',
            self::OrganisasiMuhammadiyah => 'Organisasi Muhammadiyah',
            self::KegiatanSosialDanRelawan => 'Kegiatan Sosial & Relawan',
            self::AmalUsahaDanPemberdayaan => 'Amal Usaha & Pemberdayaan',
            self::KepemimpinanDanManajemen => 'Kepemimpinan & Manajemen Organisasi',
        };
    }

    public function focusArea(): FocusArea
    {
        return match ($this) {
            self::UsahaDanUmkm,
            self::StartupDanTeknologi,
            self::KeuanganDanInvestasi,
            self::EkonomiKreatif,
            self::KewirausahaanSosial => FocusArea::Entrepreneur,
            self::KebijakanPublik,
            self::AdvokasiDanHukum,
            self::PolitikDanKeparlemenan,
            self::PemerintahanDanBirokrasi,
            self::AnalisisSosialPolitik => FocusArea::Politisi,
            self::RisetDanPublikasi,
            self::PendidikanDanPengajaran,
            self::SainsDanTeknologi,
            self::BeasiswaDanStudiLanjut,
            self::LiterasiDanPenulisan => FocusArea::Akademisi,
            self::DakwahDanKajianIslam,
            self::OrganisasiMuhammadiyah,
            self::KegiatanSosialDanRelawan,
            self::AmalUsahaDanPemberdayaan,
            self::KepemimpinanDanManajemen => FocusArea::PengabdiPersyarikatan,
        };
    }

    /**
     * Determine the dominant focus area from the selected interests.
     *
     * Returns null when no interest is selected or when multiple focus areas
     * share the highest score, so the kader can confirm their primary rumpun.
     *
     * @param  array<int, string>  $values
     */
    public static function determineFocusArea(array $values): ?FocusArea
    {
        $scores = [];

        foreach ($values as $value) {
            $interest = self::tryFrom($value);

            if ($interest === null) {
                continue;
            }

            $area = $interest->focusArea()->value;
            $scores[$area] = ($scores[$area] ?? 0) + 1;
        }

        if ($scores === []) {
            return null;
        }

        $highestScore = max($scores);
        $leaders = array_keys(array_filter(
            $scores,
            fn (int $score): bool => $score === $highestScore
        ));

        if (count($leaders) !== 1) {
            return null;
        }

        return FocusArea::from($leaders[0]);
    }
}
