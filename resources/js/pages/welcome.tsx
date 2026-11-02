import { Head, Link, usePage } from '@inertiajs/react';
import BrandLogo from '@/components/brand-logo';
import { Button } from '@/components/ui/button';
import { dashboard, home, login, register } from '@/routes';

/**
 * Placeholder content — nantinya dapat dihubungkan ke model
 * Announcement & Agenda ketika fiturnya diaktifkan Admin.
 */
const announcements = [
    {
        category: 'Pengumuman',
        date: '28 Okt 2026',
        title: 'Pembukaan Kaderisasi Gelombang II Komisariat',
        excerpt:
            'Pendaftaran kader baru gelombang kedua resmi dibuka hingga akhir bulan. Siapkan dokumen komisariat Anda.',
    },
    {
        category: 'Berita',
        date: '21 Okt 2026',
        title: 'Kader IMM UINSA Raih Beasiswa Pascasarjana',
        excerpt:
            'Dua kader rumpun Akademisi berhasil melanjutkan studi magister bidang pendidikan dan hukum tata negara.',
    },
    {
        category: 'Berita',
        date: '14 Okt 2026',
        title: 'Kunjungan Alumni Jaringan Saudagar Muhammadiyah',
        excerpt:
            'Alumni rumpun Entrepreneur berbagi pengalaman membangun unit usaha amal di lingkungan kampus.',
    },
];

const agenda = [
    { date: '08 Nov', title: 'Darul Arqam Lanjut (DAL) Angkatan 2026', place: 'Aula Kampus' },
    { date: '15 Nov', title: 'Evaluasi Tugas 1 — Rumah Pengabdian', place: 'Ruang Persyarikatan' },
    { date: '23 Nov', title: 'Diskusi Tarjih: Etika Politik di Pemilu', place: 'Masjid Kampus' },
    { date: '29 Nov', title: 'Pendampingan UMKM Bersama JSM', place: 'Digalkar UINSA' },
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
            'Kerajinan berpikir kritis: cerdas, cermat, dan penuh kesungguhan dalam menggali ilmu sebagaimana tuntutan ajaran Al-Qur\'an untuk pencerahan umat.',
        initial: 'I',
    },
    {
        title: 'Humanitas',
        description:
            'Nilai-nilai kemanusiaan yang tertanam dalam kepedulian terhadap sesama dan lingkungan, sebagai wujud dari Islam yang rahmatan lil \'alamin.',
        initial: 'H',
    },
];

export default function Welcome() {
    const { name, auth } = usePage().props;

    return (
        <>
            <Head title={`${name} — Sistem Perkaderan`} />
            <div className="theme-light flex min-h-svh flex-col bg-slate-50 text-slate-800">
                {/* ============== Top bar ============== */}
                <header className="z-10 border-b border-slate-900/10 bg-brand px-4 dark:bg-slate-900 sm:px-6">
                    <nav className="mx-auto flex h-16 w-full max-w-6xl items-center justify-between">
                        <Link
                            href={auth.user ? dashboard() : home()}
                            className="flex items-center gap-3"
                        >
                            <div className="flex size-9 items-center justify-center rounded-md bg-gold p-1.5 shrink-0">
                                <BrandLogo className="size-full" />
                            </div>
                            <div className="flex flex-col leading-tight text-white">
                                <span className="text-sm font-bold tracking-wide">{name}</span>
                                <span className="text-[11px] text-white/60">Sistem Perkaderan IMM UINSA</span>
                            </div>
                        </Link>
                        <div className="flex items-center gap-2">
                            {auth.user ? (
                                <Button asChild variant="secondary" size="sm" className="bg-slate-900 text-white hover:bg-slate-800">
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
                                        className="bg-slate-900 text-white hover:bg-gold hover:text-slate-900"
                                    >
                                        <Link href={register()}>Pendaftaran Kader</Link>
                                    </Button>
                                </>
                            )}
                        </div>
                    </nav>
                </header>

                <main>
                    {/* ============== Hero ============== */}
                    <section className="relative overflow-hidden bg-[#59040C]">
                        {/* Geometry yang disiplin, bukan ornament acak/glow */}
                        <div
                            aria-hidden="true"
                            className="pointer-events-none absolute inset-y-0 right-0 hidden w-1/2 border-l border-amber-300/15 bg-[linear-gradient(to_right,rgba(251,191,36,0.06)_1px,transparent_1px),linear-gradient(to_bottom,rgba(251,191,36,0.06)_1px,transparent_1px)] [background-size:56px_56px] lg:block"
                        />
                        <div
                            aria-hidden="true"
                            className="pointer-events-none absolute -top-24 right-[10%] hidden size-64 rounded-full border border-amber-300/20 bg-transparent lg:block"
                        />
                        <div className="relative mx-auto max-w-6xl px-4 py-20 sm:px-6 lg:py-28">
                            <div className="max-w-2xl">
                                <span className="inline-flex items-center gap-2 rounded-sm border border-amber-300/30 bg-amber-300/10 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.2em] text-amber-300">
                                    Ikatan Mahasiswa Muhammadiyah · UINSA
                                </span>
                                <h1 className="mt-6 text-4xl font-extrabold tracking-tight text-white sm:text-5xl">
                                    Cendekiawan
                                    <span className="text-amber-300"> Berakhlak Mulia</span>
                                </h1>
                                <p className="mt-4 leading-relaxed text-white/70">
                                    Platform digital perkaderan Komisariat IMM UIN Sunan Ampel
                                    Surabaya: perjalanan berjenjang yang terukur — dari
                                    verifikasi komisariat, tiga penugasan nyata, evaluasi
                                    fasilitator lapangan, hingga pembimbingan mentor senior
                                    di pilar-pilar diaspora persyarikatan.
                                </p>
                                <div className="mt-8 flex flex-wrap items-center gap-3">
                                    <Button
                                        asChild
                                        size="lg"
                                        className="bg-amber-400 font-bold text-slate-900 hover:bg-amber-300"
                                    >
                                        <Link href={register()}>Pendaftaran Kader</Link>
                                    </Button>
                                    <Button
                                        asChild
                                        size="lg"
                                        variant="outline"
                                        className="border-white/25 bg-transparent text-white hover:bg-white/10 hover:text-amber-200"
                                    >
                                        <Link href={login()}>Login Anggota</Link>
                                    </Button>
                                </div>
                            </div>

                            {/* Logo placeholder */}
                            <div className="relative mx-auto mt-14 hidden max-w-md flex-col items-center border border-amber-300/25 bg-white/5 p-10 text-center backdrop-blur-sm lg:absolute lg:right-10 lg:bottom-24 lg:mt-0 lg:flex">
                                <span className="text-[11px] font-bold uppercase tracking-[0.25em] text-amber-300/80">
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
                                    Ganti dengan berkas logo resmi IMM UINSA
                                </p>
                            </div>
                        </div>
                    </section>

                    {/* ============== Tri Kompetensi Dasar ============== */}
                    <section className="mx-auto max-w-6xl px-4 py-16 sm:px-6 lg:py-20">
                        <div className="max-w-2xl">
                            <p className="text-[11px] font-bold uppercase tracking-[0.2em] text-brand">
                                Navetta IMM
                            </p>
                            <h2 className="mt-2 text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">
                                Tri Kompetensi Dasar Pergerakan
                            </h2>
                            <p className="mt-2 text-sm leading-relaxed text-slate-500">
                                Tiga kompetensi inti yang disiplin sejak awal menjadi
                                tapak dalam teknik perkaderan organisasi mahasiswa ini.
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

                    {/* ============== Berita & Agenda ============== */}
                    <section className="border-t border-slate-200 bg-white">
                        <div className="mx-auto grid max-w-6xl gap-12 px-4 py-16 sm:px-6 lg:grid-cols-3 lg:py-20">
                            {/* Kolom info */}
                            <div className="lg:col-span-2">
                                <div className="flex items-end justify-between">
                                    <div>
                                        <p className="text-[11px] font-bold uppercase tracking-[0.2em] text-brand">
                                            Kanal Informasi
                                        </p>
                                        <h2 className="mt-2 text-2xl font-extrabold tracking-tight text-slate-900">
                                            Berita &amp; Pengumuman Persyarikatan
                                        </h2>
                                    </div>
                                </div>
                                <div className="mt-8 grid gap-6 sm:grid-cols-3">
                                    {announcements.map((item) => (
                                        <article
                                            key={item.title}
                                            className="group flex flex-col rounded-lg border border-slate-200 bg-slate-50 p-5 transition hover:border-brand/40 hover:bg-white"
                                        >
                                            <div className="flex items-center justify-between">
                                                <span className="rounded-sm bg-brand px-2 py-0.5 text-[10px] font-bold uppercase tracking-widest text-white">
                                                    {item.category}
                                                </span>
                                                <span className="text-[11px] text-slate-400">
                                                    {item.date}
                                                </span>
                                            </div>
                                            <h3 className="mt-3 text-sm font-bold leading-snug text-slate-900 group-hover:text-brand">
                                                {item.title}
                                            </h3>
                                            <p className="mt-2 text-xs leading-relaxed text-slate-500">
                                                {item.excerpt}
                                            </p>
                                        </article>
                                    ))}
                                </div>
                            </div>

                            {/* Agenda */}
                            <aside>
                                <p className="text-[11px] font-bold uppercase tracking-[0.2em] text-brand">
                                    Agenda Terdekat
                                </p>
                                <h2 className="mt-2 text-2xl font-extrabold tracking-tight text-slate-900">
                                    Kalender Kegiatan
                                </h2>
                                <ol className="mt-8 space-y-0">
                                    {agenda.map((item, index) => (
                                        <li
                                            key={item.title}
                                            className={`flex gap-4 py-4 ${index > 0 ? 'border-t border-slate-100' : ''}`}
                                        >
                                            <div className="flex size-12 shrink-0 flex-col items-center justify-center rounded-md border border-slate-200 bg-slate-50">
                                                <span className="text-[10px] font-bold uppercase text-amber-600">
                                                    {item.date.split(' ')[1]}
                                                </span>
                                                <span className="text-base font-extrabold leading-none text-slate-900">
                                                    {item.date.split(' ')[0]}
                                                </span>
                                            </div>
                                            <div>
                                                <p className="text-sm font-semibold leading-snug text-slate-900">
                                                    {item.title}
                                                </p>
                                                <p className="mt-0.5 text-xs text-slate-500">
                                                    {item.place}
                                                </p>
                                            </div>
                                        </li>
                                    ))}
                                </ol>
                            </aside>
                        </div>
                    </section>

                    {/* ============== CTA band ============== */}
                    <section className="bg-brand px-4 py-14 sm:px-6">
                        <div className="mx-auto flex max-w-6xl flex-col items-start gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <h2 className="text-xl font-extrabold tracking-tight text-white sm:text-2xl">
                                    Siap memulai perjalanan perkaderan Anda?
                                </h2>
                                <p className="mt-1 text-sm text-white/70">
                                    Daftar sebagai Kader atau Fasilitator, lalu akun Anda
                                    diverifikasi oleh Administrator komisariat.
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
                <footer className="border-t border-slate-900/10 bg-[#43040a] dark:bg-slate-950">
                    <div className="mx-auto flex max-w-6xl flex-col items-center justify-between gap-2 px-4 py-8 text-center sm:flex-row sm:px-6 sm:text-left">
                        <p className="text-xs text-white/50">
                            {name} — Sistem Informasi Perkaderan Ikatan Mahasiswa
                            Muhammadiyah UIN Sunan Ampel Surabaya.
                        </p>
                        <p className="text-[11px] tracking-widest text-amber-300/60">
                            CENDEKIAWAN · BERAKHLAK · MULIA
                        </p>
                    </div>
                </footer>
            </div>
        </>
    );
}
