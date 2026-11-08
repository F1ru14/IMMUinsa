import { Link, usePage } from '@inertiajs/react';
import { ArrowUp, Instagram, Mail, MapPin } from 'lucide-react';
import BrandLogo from '@/components/brand-logo';
import { dashboard, login, register } from '@/routes';

const exploreLinks = [
    { label: 'Beranda', href: '#beranda' },
    { label: 'Tri Kompetensi Dasar', href: '#tri-kompetensi' },
    { label: 'Berita Terkini', href: '#berita' },
    { label: 'Agenda Terdekat', href: '#agenda' },
];

const accountLinks = [
    { label: 'Pendaftaran Kader', href: register() },
    { label: 'Login Anggota', href: login() },
    { label: 'Dashboard Kader', href: dashboard() },
];

const instagramUrl = 'https://www.instagram.com/imm.uinsunanampel.sby/';

export default function LandingFooter() {
    const { name } = usePage().props;
    const year = new Date().getFullYear();

    const scrollToTop = (): void => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    };

    return (
        <footer className="relative overflow-hidden bg-[#43040a] text-white">
            {/* Multi-colored top accent bar */}
            <div aria-hidden="true" className="flex h-1.5 w-full">
                <span className="w-[38%] bg-brand" />
                <span className="w-[24%] bg-gold" />
                <span className="w-[22%] bg-brand-dark" />
                <span className="w-[16%] bg-amber-200" />
            </div>

            {/* Oversized background watermark */}
            <span
                aria-hidden="true"
                className="pointer-events-none absolute -right-4 -bottom-6 translate-x-[10%] text-[22vw] leading-none font-black tracking-tighter text-white/[0.035] select-none lg:text-[18vw]"
            >
                IMM UINSA
                {/*diganti foto*/}
            </span>

            <div className="relative mx-auto max-w-6xl px-4 py-14 sm:px-6 lg:py-16">
                <div className="grid gap-10 sm:grid-cols-2 lg:grid-cols-12 lg:gap-8">
                    {/* Column 1 — logo card, description, tagline */}
                    <div className="lg:col-span-5">
                        <div className="inline-flex items-center gap-3">
                            <div className="flex size-18 shrink-0 items-center justify-center">
                                <BrandLogo className="size-full" />
                            </div>
                            <div>
                                <p className="text-sm font-bold tracking-wide text-white">
                                    {name}
                                </p>
                                <p className="text-[10px] font-semibold tracking-[0.2em] text-amber-300/80 uppercase">
                                    Sistem Perkaderan
                                </p>
                            </div>
                        </div>

                        <p className="mt-6 max-w-md text-sm leading-relaxed text-white/60">
                            Website resmi perkaderan Komisariat IMM UIN Sunan
                            Ampel Surabaya. Wadah perjuangan dan pengkaderan
                            Ikatan Mahasiswa Muhammadiyah yang terukur dan
                            berjenjang.
                        </p>

                        <div className="mt-6 flex items-center gap-3">
                            <span className="h-0.5 w-10 bg-gold" />
                            <p className="text-[11px] font-bold tracking-[0.25em] text-amber-300 uppercase">
                                Cendekiawan · Berakhlak · Mulia
                            </p>
                        </div>
                    </div>

                    {/* Column 2 — explore navigation */}
                    <div className="lg:col-span-2">
                        <h3 className="text-[11px] font-bold tracking-[0.2em] text-amber-300/80 uppercase">
                            Jelajahi
                        </h3>
                        <ul className="mt-5 space-y-3">
                            {exploreLinks.map((item) => (
                                <li key={item.label}>
                                    <a
                                        href={item.href}
                                        className="text-sm text-white/60 transition hover:text-amber-300"
                                    >
                                        {item.label}
                                    </a>
                                </li>
                            ))}
                        </ul>
                    </div>

                    {/* Column 3 — portal kader */}
                    <div className="lg:col-span-2">
                        <h3 className="text-[11px] font-bold tracking-[0.2em] text-amber-300/80 uppercase">
                            Portal Kader
                        </h3>
                        <ul className="mt-5 space-y-3">
                            {accountLinks.map((item) => (
                                <li key={item.label}>
                                    <Link
                                        href={item.href}
                                        className="text-sm text-white/60 transition hover:text-amber-300"
                                    >
                                        {item.label}
                                    </Link>
                                </li>
                            ))}
                        </ul>
                    </div>

                    {/* Column 4 — address & social media */}
                    <div className="lg:col-span-3">
                        <h3 className="text-[11px] font-bold tracking-[0.2em] text-amber-300/80 uppercase">
                            Sekretariat
                        </h3>
                        <address className="mt-5 flex gap-3 text-sm leading-relaxed text-white/60 not-italic">
                            <MapPin className="mt-0.5 size-4 shrink-0 text-gold" />
                            <span>
                                Jl. Pabrik Kulit III/25 Surabaya
                                <br />
                                Jawa Timur 60237
                            </span>
                        </address>
                        <a
                            href="mailto:imm.uinsa@gmail.com"
                            className="mt-3 flex items-center gap-3 text-sm text-white/60 transition hover:text-amber-300"
                        >
                            <Mail className="size-4 shrink-0 text-gold" />
                            <span>imm.uinsa@gmail.com</span>
                        </a>
                        <a
                            href={instagramUrl}
                            target="_blank"
                            rel="noreferrer"
                            aria-label="Instagram IMM UINSA"
                            className="mt-3 flex items-center gap-3 text-sm text-white/60 transition hover:text-amber-300"
                        >
                            <Instagram className="size-4 shrink-0 text-gold" />
                            <span>@imm.uinsunanampel.sby</span>
                        </a>
                    </div>
                </div>
            </div>

            {/* Sub-footer */}
            <div className="relative border-t border-white/10">
                <div className="mx-auto flex max-w-6xl flex-col items-start gap-4 px-4 py-6 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                    <p className="text-xs text-white/50">
                        © {year} {name}.
                    </p>
                    <p className="text-[10px] font-semibold tracking-[0.2em] text-amber-300/60 uppercase">
                        Sistem Informasi Perkaderan IMM UINSA
                    </p>
                    <button
                        type="button"
                        onClick={scrollToTop}
                        aria-label="Kembali ke atas"
                        className="flex size-10 shrink-0 items-center justify-center rounded-full border border-white/20 bg-[#43040a] text-white/70 shadow-lg shadow-black/30 transition hover:-translate-y-0.5 hover:border-gold hover:text-gold"
                    >
                        <ArrowUp className="size-4" />
                    </button>
                </div>
            </div>
        </footer>
    );
}
