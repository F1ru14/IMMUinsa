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
        <footer className="border-t border-white/10 bg-brand-ink text-white">
            <div className="mx-auto max-w-6xl px-4 py-14 sm:px-6 lg:py-16">
                <div className="grid gap-10 sm:grid-cols-2 lg:grid-cols-12 lg:gap-8">
                    {/* Brand */}
                    <div className="lg:col-span-5">
                        <div className="inline-flex items-center gap-3">
                            <BrandLogo className="h-8 w-auto" />
                            <span className="leading-tight">
                                <span className="block text-sm font-semibold text-white">
                                    {name}
                                </span>
                                <span className="block text-xs text-white/50">
                                    Platform Digital Perkaderan
                                </span>
                            </span>
                        </div>

                        <p className="mt-6 max-w-md text-sm leading-relaxed text-white/60">
                            Website resmi perkaderan Komisariat IMM UIN Sunan
                            Ampel Surabaya. Wadah perjuangan dan pengkaderan
                            Ikatan Mahasiswa Muhammadiyah yang terukur dan
                            berjenjang.
                        </p>

                        <p className="mt-6 text-xs font-semibold tracking-[0.18em] text-amber-300 uppercase">
                            Cendekiawan · Berakhlak · Mulia
                        </p>
                    </div>

                    {/* Explore */}
                    <div className="lg:col-span-2">
                        <h3 className="text-xs font-medium text-white/45">
                            Jelajahi
                        </h3>
                        <ul className="mt-4 space-y-2.5">
                            {exploreLinks.map((item) => (
                                <li key={item.label}>
                                    <a
                                        href={item.href}
                                        className="text-sm text-white/65 transition hover:text-white"
                                    >
                                        {item.label}
                                    </a>
                                </li>
                            ))}
                        </ul>
                    </div>

                    {/* Portal */}
                    <div className="lg:col-span-2">
                        <h3 className="text-xs font-medium text-white/45">
                            Portal kader
                        </h3>
                        <ul className="mt-4 space-y-2.5">
                            {accountLinks.map((item) => (
                                <li key={item.label}>
                                    <Link
                                        href={item.href}
                                        className="text-sm text-white/65 transition hover:text-white"
                                    >
                                        {item.label}
                                    </Link>
                                </li>
                            ))}
                        </ul>
                    </div>

                    {/* Contact */}
                    <div className="lg:col-span-3">
                        <h3 className="text-xs font-medium text-white/45">
                            Sekretariat
                        </h3>
                        <address className="mt-4 flex gap-3 text-sm leading-relaxed text-white/65 not-italic">
                            <MapPin className="mt-0.5 size-4 shrink-0 text-amber-300" />
                            <span>
                                Jl. Pabrik Kulit III/25 Surabaya
                                <br />
                                Jawa Timur 60237
                            </span>
                        </address>
                        <a
                            href="mailto:imm.uinsa@gmail.com"
                            className="mt-3 flex items-center gap-3 text-sm text-white/65 transition hover:text-white"
                        >
                            <Mail className="size-4 shrink-0 text-amber-300" />
                            <span>imm.uinsa@gmail.com</span>
                        </a>
                        <a
                            href={instagramUrl}
                            target="_blank"
                            rel="noreferrer"
                            aria-label="Instagram IMM UINSA"
                            className="mt-3 flex items-center gap-3 text-sm text-white/65 transition hover:text-white"
                        >
                            <Instagram className="size-4 shrink-0 text-amber-300" />
                            <span>@imm.uinsunanampel.sby</span>
                        </a>
                    </div>
                </div>
            </div>

            <div className="border-t border-white/10">
                <div className="mx-auto flex max-w-6xl flex-col items-start gap-3 px-4 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                    <p className="text-xs text-white/45">
                        © {year} {name}
                    </p>
                    <button
                        type="button"
                        onClick={scrollToTop}
                        className="inline-flex items-center gap-1.5 text-xs text-white/55 transition hover:text-white"
                    >
                        Kembali ke atas
                        <ArrowUp className="size-3.5" />
                    </button>
                </div>
            </div>
        </footer>
    );
}
