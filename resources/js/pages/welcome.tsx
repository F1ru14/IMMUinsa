import { Head, Link, usePage } from '@inertiajs/react';
import { ArrowUpRight } from 'lucide-react';
import BrandLogo from '@/components/brand-logo';
import LandingFooter from '@/components/landing-footer';
import { Button } from '@/components/ui/button';
import { dashboard, home, login, register } from '@/routes';

type NewsItem = {
    title: string;
    url: string;
    source: string;
    date: string;
    excerpt: string;
    category: string;
    image: string | null;
};

/**
 * Fallback statis — dipakai hanya bila feed berita sedang tidak dapat
 * diakses dan cache cadangan masih kosong.
 */
const fallbackNews: NewsItem[] = [
    {
        category: 'Berita',
        date: '28 Okt 2026',
        title: 'Pembukaan Kaderisasi Gelombang II Komisariat',
        excerpt:
            'Pendaftaran kader baru gelombang kedua resmi dibuka hingga akhir bulan. Siapkan dokumen komisariat Anda.',
        url: 'https://www.immuinsasby.com/',
        source: 'immuinsasby.com',
        image: null,
    },
    {
        category: 'Berita',
        date: '21 Okt 2026',
        title: 'Kader IMM UINSA Raih Beasiswa Pascasarjana',
        excerpt:
            'Dua kader rumpun Akademisi berhasil melanjutkan studi magister bidang pendidikan dan hukum tata negara.',
        url: 'https://www.immuinsasby.com/',
        source: 'immuinsasby.com',
        image: null,
    },
    {
        category: 'Berita',
        date: '14 Okt 2026',
        title: 'Kunjungan Alumni Jaringan Saudagar Muhammadiyah',
        excerpt:
            'Alumni rumpun Entrepreneur berbagi pengalaman membangun unit usaha amal di lingkungan kampus.',
        url: 'https://www.immuinsasby.com/',
        source: 'immuinsasby.com',
        image: null,
    },
];

const agenda = [
    {
        date: '08 Nov',
        title: 'Darul Arqam Lanjut (DAL) Angkatan 2026',
        place: 'Aula Kampus',
    },
    {
        date: '15 Nov',
        title: 'Evaluasi Tugas 1 — Rumah Pengabdian',
        place: 'Ruang Persyarikatan',
    },
    {
        date: '23 Nov',
        title: 'Diskusi Tarjih: Etika Politik di Pemilu',
        place: 'Masjid Kampus',
    },
    {
        date: '29 Nov',
        title: 'Pendampingan UMKM Bersama JSM',
        place: 'Digalkar UINSA',
    },
];

const triKompetensi = [
    {
        title: 'Religiusitas',
        description:
            'Ketaatan yang mendalam kepada Allah SWT dan contoh keteladanan Rasulullah SAW sebagai landasan gerak bersama dalam muamalah dan keorganisasian.',
    },
    {
        title: 'Intelektualitas',
        description:
            "Kerajinan berpikir kritis: cerdas, cermat, dan penuh kesungguhan dalam menggali ilmu sebagaimana tuntutan ajaran Al-Qur'an untuk pencerahan umat.",
    },
    {
        title: 'Humanitas',
        description:
            "Nilai-nilai kemanusiaan yang tertanam dalam kepedulian terhadap sesama dan lingkungan, sebagai wujud dari Islam yang rahmatan lil 'alamin.",
    },
];

const journey = [
    {
        title: 'Verifikasi komisariat',
        description: 'Akun kader diverifikasi pengurus komisariat.',
    },
    {
        title: 'Biodata & fokus bidang',
        description: 'Minat dipetakan ke satu dari empat rumpun pergerakan.',
    },
    {
        title: 'Tiga penugasan',
        description: 'Tugas lapangan dievaluasi fasilitator pendamping.',
    },
    {
        title: 'Pendampingan mentor',
        description: 'Kader terhubung dengan mentor senior persyarikatan.',
    },
];

export default function Welcome() {
    const { name, auth, news } = usePage<{ news?: NewsItem[] }>().props;
    const latestNews = news && news.length > 0 ? news : fallbackNews;

    return (
        <>
            <Head title={`${name} — Platform Digital Perkaderan`} />
            <div className="theme-light flex min-h-svh flex-col bg-slate-50 pt-16 text-slate-800">
                {/* Top bar */}
                <header className="fixed inset-x-0 top-0 z-50 border-b border-white/10 bg-brand">
                    <nav className="mx-auto flex h-16 w-full max-w-6xl items-center justify-between px-4 sm:px-6">
                        <Link
                            href={auth.user ? dashboard() : home()}
                            className="flex items-center gap-3"
                        >
                            <BrandLogo className="h-8 w-auto" />
                            <span className="hidden flex-col leading-tight text-white sm:flex">
                                <span className="text-sm font-semibold">
                                    {name}
                                </span>
                                <span className="text-[11px] text-white/55">
                                    Perkaderan IMM UINSA
                                </span>
                            </span>
                        </Link>
                        <div className="flex items-center gap-1">
                            {auth.user ? (
                                <Button
                                    asChild
                                    size="sm"
                                    className="bg-white font-semibold text-brand shadow-none hover:bg-white/90"
                                >
                                    <Link href={dashboard()}>Dashboard</Link>
                                </Button>
                            ) : (
                                <>
                                    <Link
                                        href={login()}
                                        className="rounded-md px-3 py-2 text-sm font-medium text-white/75 transition hover:text-white"
                                    >
                                        Masuk
                                    </Link>
                                    <Button
                                        asChild
                                        size="sm"
                                        className="bg-gold font-semibold text-slate-900 shadow-none hover:bg-amber-300"
                                    >
                                        <Link href={register()}>Daftar</Link>
                                    </Button>
                                </>
                            )}
                        </div>
                    </nav>
                </header>

                <main>
                    {/* Hero */}
                    <section id="beranda" className="scroll-mt-16 bg-[#59040C]">
                        <div className="mx-auto grid w-full max-w-6xl items-center gap-12 px-4 py-24 sm:px-6 lg:min-h-[calc(100svh-4rem)] lg:grid-cols-[minmax(0,1fr)_20rem]">
                            <div className="max-w-2xl">
                                <p className="text-xs font-semibold tracking-[0.18em] text-amber-300 uppercase">
                                    Ikatan Mahasiswa Muhammadiyah · UINSA
                                </p>
                                <h1 className="mt-4 text-4xl font-bold tracking-tight text-white sm:text-5xl lg:text-6xl">
                                    Cendekiawan
                                    <span className="block text-amber-300">
                                        berakhlak mulia
                                    </span>
                                </h1>
                                <p className="mt-5 max-w-xl text-base leading-relaxed text-white/70">
                                    Platform digital perkaderan Komisariat IMM
                                    UIN Sunan Ampel Surabaya: perjalanan
                                    berjenjang yang terukur — dari verifikasi
                                    komisariat, tiga penugasan nyata, evaluasi
                                    fasilitator lapangan, hingga pembimbingan
                                    mentor senior di pilar-pilar diaspora
                                    persyarikatan.
                                </p>
                                <div className="mt-8 flex flex-wrap items-center gap-3">
                                    <Button
                                        asChild
                                        size="lg"
                                        className="bg-amber-400 font-semibold text-slate-900 shadow-none hover:bg-amber-300"
                                    >
                                        <Link href={register()}>
                                            Daftar sebagai kader
                                        </Link>
                                    </Button>
                                    <Button
                                        asChild
                                        size="lg"
                                        variant="outline"
                                        className="border-white/25 bg-transparent text-white shadow-none hover:bg-white/10 hover:text-white"
                                    >
                                        <Link href={login()}>Masuk</Link>
                                    </Button>
                                </div>
                            </div>

                            <ol className="hidden border-l border-white/15 pl-6 lg:block">
                                <p className="text-xs font-semibold tracking-[0.18em] text-amber-300 uppercase">
                                    Alur perkaderan
                                </p>
                                {journey.map((step, index) => (
                                    <li
                                        key={step.title}
                                        className="mt-5 flex gap-4"
                                    >
                                        <span className="w-4 shrink-0 text-xs font-semibold text-amber-300/80">
                                            {String(index + 1).padStart(2, '0')}
                                        </span>
                                        <span>
                                            <span className="block text-sm font-semibold text-white">
                                                {step.title}
                                            </span>
                                            <span className="mt-0.5 block text-xs leading-relaxed text-white/55">
                                                {step.description}
                                            </span>
                                        </span>
                                    </li>
                                ))}
                            </ol>
                        </div>
                    </section>

                    {/* Tri Kompetensi Dasar */}
                    <section
                        id="tri-kompetensi"
                        className="mx-auto w-full max-w-6xl scroll-mt-16 px-4 py-20 sm:px-6"
                    >
                        <div className="max-w-2xl border-b border-slate-200 pb-6">
                            <p className="text-xs font-semibold tracking-[0.18em] text-brand uppercase">
                                Kompetensi dasar
                            </p>
                            <h2 className="mt-2 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                                Tri Kompetensi Dasar Pergerakan
                            </h2>
                            <p className="mt-2 text-sm leading-relaxed text-slate-500">
                                Tiga kompetensi inti yang menjadi tapak teknik
                                perkaderan organisasi mahasiswa ini.
                            </p>
                        </div>
                        <div className="mt-8 grid gap-px overflow-hidden rounded-lg border border-slate-200 bg-slate-200 sm:grid-cols-3">
                            {triKompetensi.map((pillar, index) => (
                                <div
                                    key={pillar.title}
                                    className="bg-white p-6"
                                >
                                    <span className="text-xs font-semibold text-slate-400">
                                        {String(index + 1).padStart(2, '0')}
                                    </span>
                                    <h3 className="mt-4 text-base font-semibold text-slate-900">
                                        {pillar.title}
                                    </h3>
                                    <p className="mt-2 text-sm leading-relaxed text-slate-500">
                                        {pillar.description}
                                    </p>
                                </div>
                            ))}
                        </div>
                    </section>

                    {/* Berita Terkini */}
                    <section
                        id="berita"
                        className="scroll-mt-16 border-t border-slate-200 bg-white"
                    >
                        <div className="mx-auto w-full max-w-6xl px-4 py-20 sm:px-6">
                            <div className="flex flex-col gap-4 border-b border-slate-200 pb-6 sm:flex-row sm:items-end sm:justify-between">
                                <div className="max-w-2xl">
                                    <p className="text-xs font-semibold tracking-[0.18em] text-brand uppercase">
                                        Kanal informasi
                                    </p>
                                    <h2 className="mt-2 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                                        Berita Terkini
                                    </h2>
                                    <p className="mt-2 text-sm leading-relaxed text-slate-500">
                                        Berita resmi Komisariat IMM UIN Sunan
                                        Ampel Surabaya.
                                    </p>
                                </div>
                                <a
                                    href="https://www.immuinsasby.com/"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    className="inline-flex shrink-0 items-center gap-1 text-xs font-semibold text-brand hover:underline"
                                >
                                    Kunjungi immuinsasby.com
                                    <ArrowUpRight className="size-3.5" />
                                </a>
                            </div>

                            <div className="mt-8 grid gap-6 sm:grid-cols-3">
                                {latestNews.map((item) => (
                                    <a
                                        key={item.title}
                                        href={item.url}
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        className="group flex flex-col overflow-hidden rounded-lg border border-slate-200 transition hover:border-brand/40"
                                    >
                                        <div className="aspect-[16/9] w-full overflow-hidden bg-slate-100">
                                            {item.image ? (
                                                <img
                                                    src={item.image}
                                                    alt=""
                                                    loading="lazy"
                                                    decoding="async"
                                                    className="size-full object-cover"
                                                />
                                            ) : (
                                                <div className="flex size-full items-center justify-center">
                                                    <BrandLogo className="h-8 w-auto opacity-25" />
                                                </div>
                                            )}
                                        </div>
                                        <div className="flex flex-1 flex-col p-5">
                                            <div className="flex items-center justify-between gap-3 text-xs">
                                                <span className="font-semibold text-brand">
                                                    {item.category}
                                                </span>
                                                <span className="text-slate-400">
                                                    {item.date}
                                                </span>
                                            </div>
                                            <h3 className="mt-2 text-sm leading-snug font-semibold text-slate-900 group-hover:text-brand">
                                                {item.title}
                                            </h3>
                                            <p className="mt-2 text-xs leading-relaxed text-slate-500">
                                                {item.excerpt}
                                            </p>
                                            <span className="mt-auto inline-flex items-center gap-1 pt-4 text-xs font-semibold text-brand">
                                                Baca selengkapnya
                                                <ArrowUpRight className="size-3" />
                                            </span>
                                        </div>
                                    </a>
                                ))}
                            </div>
                        </div>
                    </section>

                    {/* Agenda Terdekat */}
                    <section
                        id="agenda"
                        className="scroll-mt-16 border-t border-slate-200 bg-slate-50"
                    >
                        <div className="mx-auto w-full max-w-6xl px-4 py-20 sm:px-6">
                            <div className="max-w-2xl border-b border-slate-200 pb-6">
                                <p className="text-xs font-semibold tracking-[0.18em] text-brand uppercase">
                                    Agenda terdekat
                                </p>
                                <h2 className="mt-2 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                                    Kalender Kegiatan
                                </h2>
                                <p className="mt-2 text-sm leading-relaxed text-slate-500">
                                    Jadwal kegiatan komisariat yang akan datang.
                                </p>
                            </div>

                            <ol className="mt-8 grid gap-px overflow-hidden rounded-lg border border-slate-200 bg-slate-200 sm:grid-cols-2 lg:grid-cols-4">
                                {agenda.map((item) => (
                                    <li
                                        key={item.title}
                                        className="flex gap-4 bg-white p-5"
                                    >
                                        <div className="flex size-12 shrink-0 flex-col items-center justify-center rounded border border-slate-200">
                                            <span className="text-[10px] font-semibold text-brand uppercase">
                                                {item.date.split(' ')[1]}
                                            </span>
                                            <span className="text-base leading-none font-bold text-slate-900">
                                                {item.date.split(' ')[0]}
                                            </span>
                                        </div>
                                        <div>
                                            <p className="text-sm leading-snug font-semibold text-slate-900">
                                                {item.title}
                                            </p>
                                            <p className="mt-0.5 text-xs text-slate-500">
                                                {item.place}
                                            </p>
                                        </div>
                                    </li>
                                ))}
                            </ol>
                        </div>
                    </section>
                </main>

                <LandingFooter />
            </div>
        </>
    );
}
