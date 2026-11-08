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
        initial: 'R',
    },
    {
        title: 'Intelektualitas',
        description:
            "Kerajinan berpikir kritis: cerdas, cermat, dan penuh kesungguhan dalam menggali ilmu sebagaimana tuntutan ajaran Al-Qur'an untuk pencerahan umat.",
        initial: 'I',
    },
    {
        title: 'Humanitas',
        description:
            "Nilai-nilai kemanusiaan yang tertanam dalam kepedulian terhadap sesama dan lingkungan, sebagai wujud dari Islam yang rahmatan lil 'alamin.",
        initial: 'H',
    },
];

export default function Welcome() {
    const { name, auth, news } = usePage<{ news?: NewsItem[] }>().props;
    const latestNews = news && news.length > 0 ? news : fallbackNews;

    return (
        <>
            <Head title={`${name} — Sistem Perkaderan`} />
            <div className="theme-light flex min-h-svh flex-col bg-slate-50 pt-16 text-slate-800">
                {/* ============== Top bar ============== */}
                <header className="fixed inset-x-0 top-0 z-50 border-b border-slate-900/10 bg-brand/95 px-4 backdrop-blur-sm sm:px-6">
                    <nav className="mx-auto flex h-16 w-full max-w-6xl items-center justify-between">
                        <Link
                            href={auth.user ? dashboard() : home()}
                            className="flex items-center gap-3"
                        >
                            <div className="flex size-20 items-center justify-center">
                                <BrandLogo className="size-full" />
                            </div>
                            <div className="flex flex-col leading-tight text-white">
                                <span className="text-sm font-bold tracking-wide">
                                    {name}
                                </span>
                                <span className="text-[11px] text-white/60">
                                    Sistem Perkaderan IMM UINSA
                                </span>
                            </div>
                        </Link>
                        <div className="flex items-center gap-2">
                            {auth.user ? (
                                <Button
                                    asChild
                                    variant="secondary"
                                    size="sm"
                                    className="bg-slate-900 text-white hover:bg-slate-800"
                                >
                                    <Link href={dashboard()}>Dashboard</Link>
                                </Button>
                            ) : (
                                <>
                                    <Link
                                        href={login()}
                                        className="rounded-lg px-4 py-2 text-sm font-semibold text-white/80 transition hover:text-amber-300"
                                    >
                                        Masuk
                                    </Link>
                                    <Button
                                        asChild
                                        size="sm"
                                        className="rounded-lg px-4 py-2 text-sm font-semibold text-white/80 transition hover:text-amber-300"
                                    >
                                        <Link href={register()}>
                                            Pendaftaran Kader
                                        </Link>
                                    </Button>
                                </>
                            )}
                        </div>
                    </nav>
                </header>

                <main>
                    {/* ============== Hero ============== */}
                    <section
                        id="beranda"
                        className="relative flex min-h-screen scroll-mt-16 flex-col justify-center overflow-hidden bg-[#59040C]"
                    >
                        {/* Geometry yang disiplin, bukan ornament acak/glow */}
                        <div
                            aria-hidden="true"
                            className="pointer-events-none absolute inset-y-0 right-0 hidden w-1/2 border-l border-amber-300/15 bg-[linear-gradient(to_right,rgba(251,191,36,0.06)_1px,transparent_1px),linear-gradient(to_bottom,rgba(251,191,36,0.06)_1px,transparent_1px)] [background-size:56px_56px] lg:block"
                        />
                        <div
                            aria-hidden="true"
                            className="pointer-events-none absolute -top-24 right-[10%] hidden size-64 rounded-full border border-amber-300/20 bg-transparent lg:block"
                        />
                        <div className="relative mx-auto grid max-w-6xl items-center gap-12 px-4 py-20 sm:px-6 lg:grid-cols-[minmax(0,1fr)_24rem] lg:py-28">
                            <div className="max-w-2xl">
                                <span className="inline-flex items-center gap-2 rounded-sm border border-amber-300/30 bg-amber-300/10 px-3 py-1 text-[11px] font-bold tracking-[0.2em] text-amber-300 uppercase">
                                    Ikatan Mahasiswa Muhammadiyah · UINSA
                                </span>
                                <h1 className="mt-6 text-4xl font-extrabold tracking-tight text-white sm:text-5xl">
                                    Cendekiawan
                                    <span className="text-amber-300">
                                        {' '}
                                        Berakhlak Mulia
                                    </span>
                                </h1>
                                <p className="mt-4 leading-relaxed text-white/70">
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
                                        className="bg-amber-400 font-bold text-slate-900 hover:bg-amber-300"
                                    >
                                        <Link href={register()}>
                                            Pendaftaran Kader
                                        </Link>
                                    </Button>
                                    <Button
                                        asChild
                                        size="lg"
                                        variant="outline"
                                        className="border-white/25 bg-transparent text-white hover:bg-white/10 hover:text-amber-200"
                                    >
                                        <Link href={login()}>
                                            Login Anggota
                                        </Link>
                                    </Button>
                                </div>
                            </div>

                            {/* Logo placeholder */}
                            <div className="mx-auto hidden w-full max-w-sm flex-col items-center border border-amber-300/25 bg-white/5 p-10 text-center backdrop-blur-sm lg:flex">
                                <span className="text-[11px] font-bold tracking-[0.25em] text-amber-300/80 uppercase">
                                    UIN Sunan Ampel Surabaya
                                </span>
                                <div className="mt-6 flex size-28 items-center justify-center rounded-full border border-amber-300/30 bg-white/5">
                                    <div className="flex size-20 items-center justify-center rounded-full bg-gold p-4">
                                        <BrandLogo className="size-full" />
                                    </div>
                                </div>
                                <p className="mt-6 text-sm font-semibold text-white">
                                    Logo Resmi Komisariat
                                </p>
                                <p className="mt-1 text-xs text-white/50">
                                    Komisariat IMM UIN Sunan Ampel Surabaya
                                </p>
                            </div>
                        </div>
                    </section>

                    {/* ============== Tri Kompetensi Dasar ============== */}
                    <section
                        id="tri-kompetensi"
                        className="mx-auto flex min-h-screen w-full max-w-6xl scroll-mt-16 flex-col justify-center px-4 py-16 sm:px-6 lg:py-20"
                    >
                        <div className="max-w-2xl">
                            <p className="text-[11px] font-bold tracking-[0.2em] text-brand uppercase">
                                Navetta IMM
                            </p>
                            <h2 className="mt-2 text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">
                                Tri Kompetensi Dasar Pergerakan
                            </h2>
                            <p className="mt-2 text-sm leading-relaxed text-slate-500">
                                Tiga kompetensi inti yang disiplin sejak awal
                                menjadi tapak dalam teknik perkaderan organisasi
                                mahasiswa ini.
                            </p>
                        </div>
                        <div className="mt-10 grid gap-px overflow-hidden rounded-xl border border-slate-200 bg-slate-200 sm:grid-cols-3">
                            {triKompetensi.map((pillar) => (
                                <div
                                    key={pillar.title}
                                    className="bg-white p-8 transition hover:bg-brand-soft"
                                >
                                    <div className="flex size-10 items-center justify-center rounded-md bg-brand font-extrabold text-white">
                                        {pillar.initial}
                                    </div>
                                    <h3 className="mt-5 text-base font-bold text-slate-900">
                                        {pillar.title}
                                    </h3>
                                    <p className="mt-2 text-sm leading-relaxed text-slate-500">
                                        {pillar.description}
                                    </p>
                                    <div className="mt-5 h-0.5 w-8 bg-amber-400" />
                                </div>
                            ))}
                        </div>
                    </section>

                    {/* ============== Berita Terkini ============== */}
                    <section
                        id="berita"
                        className="flex min-h-screen scroll-mt-16 flex-col justify-center border-t border-slate-200 bg-white"
                    >
                        <div className="mx-auto w-full max-w-6xl px-4 py-16 sm:px-6 lg:py-20">
                            <div className="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                                <div className="max-w-2xl">
                                    <p className="text-[11px] font-bold tracking-[0.2em] text-brand uppercase">
                                        Kanal Informasi
                                    </p>
                                    <h2 className="mt-2 text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">
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

                            <div className="mt-10 grid gap-6 sm:grid-cols-3">
                                {latestNews.map((item) => (
                                    <a
                                        key={item.title}
                                        href={item.url}
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        className="group flex flex-col overflow-hidden rounded-lg border border-slate-200 bg-slate-50 transition hover:border-brand/40 hover:bg-white"
                                    >
                                        <div className="aspect-[16/9] w-full overflow-hidden bg-slate-200">
                                            {item.image ? (
                                                <img
                                                    src={item.image}
                                                    alt=""
                                                    loading="lazy"
                                                    decoding="async"
                                                    className="size-full object-cover transition duration-300 group-hover:scale-105"
                                                />
                                            ) : (
                                                <div className="flex size-full items-center justify-center bg-brand/5">
                                                    <BrandLogo className="size-12 opacity-30" />
                                                </div>
                                            )}
                                        </div>
                                        <div className="flex flex-1 flex-col p-5">
                                            <div className="flex items-center justify-between">
                                                <span className="rounded-sm bg-brand px-2 py-0.5 text-[10px] font-bold tracking-widest text-white uppercase">
                                                    {item.category}
                                                </span>
                                                <span className="text-[11px] text-slate-400">
                                                    {item.date}
                                                </span>
                                            </div>
                                            <h3 className="mt-3 text-sm leading-snug font-bold text-slate-900 group-hover:text-brand">
                                                {item.title}
                                            </h3>
                                            <p className="mt-2 text-xs leading-relaxed text-slate-500">
                                                {item.excerpt}
                                            </p>
                                            <span className="mt-auto inline-flex items-center gap-1 pt-4 text-[11px] font-semibold text-brand">
                                                Baca selengkapnya
                                                <ArrowUpRight className="size-3" />
                                            </span>
                                        </div>
                                    </a>
                                ))}
                            </div>
                        </div>
                    </section>

                    {/* ============== Agenda Terdekat ============== */}
                    <section
                        id="agenda"
                        className="scroll-mt-16 border-t border-slate-200 bg-slate-50"
                    >
                        <div className="mx-auto w-full max-w-6xl px-4 py-16 sm:px-6 lg:py-20">
                            <div className="max-w-2xl">
                                <p className="text-[11px] font-bold tracking-[0.2em] text-brand uppercase">
                                    Agenda Terdekat
                                </p>
                                <h2 className="mt-2 text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">
                                    Kalender Kegiatan
                                </h2>
                                <p className="mt-2 text-sm leading-relaxed text-slate-500">
                                    Jadwal kegiatan komisariat yang akan datang.
                                </p>
                            </div>

                            <ol className="mt-10 grid gap-px overflow-hidden rounded-xl border border-slate-200 bg-slate-200 sm:grid-cols-2 lg:grid-cols-4">
                                {agenda.map((item) => (
                                    <li
                                        key={item.title}
                                        className="flex gap-4 bg-white p-5"
                                    >
                                        <div className="flex size-12 shrink-0 flex-col items-center justify-center rounded-md border border-slate-200 bg-slate-50">
                                            <span className="text-[10px] font-bold text-amber-600 uppercase">
                                                {item.date.split(' ')[1]}
                                            </span>
                                            <span className="text-base leading-none font-extrabold text-slate-900">
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

                    {/* ============== CTA band ============== */}
                    <section
                        id="daftar"
                        className="flex min-h-screen scroll-mt-16 flex-col justify-center bg-brand px-4 py-14 sm:px-6"
                    >
                        <div className="mx-auto flex max-w-6xl flex-col items-start gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <h2 className="text-xl font-extrabold tracking-tight text-white sm:text-2xl">
                                    Siap memulai perjalanan perkaderan Anda?
                                </h2>
                                <p className="mt-1 text-sm text-white/70">
                                    Daftar sebagai Kader atau Fasilitator, lalu
                                    akun Anda diverifikasi oleh Administrator
                                    komisariat.
                                </p>
                            </div>
                            <Button
                                asChild
                                size="lg"
                                className="shrink-0 bg-amber-400 font-bold text-slate-900 hover:bg-amber-300"
                            >
                                <Link href={register()}>Daftar Sekarang</Link>
                            </Button>
                        </div>
                    </section>
                </main>

                {/* ============== Footer ============== */}
                <LandingFooter />
            </div>
        </>
    );
}
