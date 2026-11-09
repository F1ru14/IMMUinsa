import { Link, usePage } from '@inertiajs/react';
import BrandLogo from '@/components/brand-logo';
import { home } from '@/routes';
import type { AuthLayoutProps } from '@/types';

const brandValues = ['Religiusitas', 'Intelektualitas', 'Humanitas'];

export default function AuthSimpleLayout({
    children,
    title,
    description,
}: AuthLayoutProps) {
    const { name } = usePage().props;

    return (
        // `theme-light` mengunci token warna terang agar halaman auth tetap
        // terbaca walau sistem pengguna memakai dark mode.
        <div className="theme-light grid min-h-svh bg-slate-50 lg:grid-cols-2">
            {/* ============== Panel brand (maroon darah IMM) ============== */}
            <div className="relative hidden flex-col justify-between overflow-hidden bg-[#59040C] p-10 text-white lg:flex">
                {/* Grid geometris disiplin, bukan gradien neon */}
                <div
                    aria-hidden="true"
                    className="pointer-events-none absolute inset-0 border-r border-amber-300/10 bg-[linear-gradient(to_right,rgba(251,191,36,0.05)_1px,transparent_1px),linear-gradient(to_bottom,rgba(251,191,36,0.05)_1px,transparent_1px)] [background-size:56px_56px]"
                />

                <Link
                    href={home()}
                    className="relative z-10 flex items-center gap-3"
                >
                    <div className="flex size-9 items-center justify-center rounded-md bg-gold p-1.5">
                        <BrandLogo className="size-full" />
                    </div>
                    <div className="leading-tight">
                        <p className="text-sm font-bold tracking-wide">
                            {name}
                        </p>
                        <p className="text-[11px] text-white/60">
                            Platform Digital Perkaderan IMM UINSA
                        </p>
                    </div>
                </Link>

                <div className="relative z-10 max-w-md">
                    <span className="inline-flex items-center rounded-sm border border-amber-300/30 bg-amber-300/10 px-3 py-1 text-[11px] font-bold tracking-[0.2em] text-amber-300 uppercase">
                        Komisariat UIN Sunan Ampel
                    </span>
                    <h2 className="mt-5 text-3xl font-extrabold tracking-tight">
                        Cendekiawan
                        <span className="text-amber-300"> Berakhlak Mulia</span>
                    </h2>
                    <p className="mt-3 text-sm leading-relaxed text-white/65">
                        Portal perkaderan berjenjang: verifikasi komisariat,
                        penugasan nyata, evaluasi fasilitator, hingga mentoring
                        senior persyarikatan.
                    </p>
                    <div className="mt-6 flex gap-2">
                        {brandValues.map((value) => (
                            <span
                                key={value}
                                className="rounded-sm border border-amber-300/25 bg-white/5 px-2.5 py-1 text-[11px] font-semibold text-amber-200/90"
                            >
                                {value}
                            </span>
                        ))}
                    </div>
                </div>

                <p className="relative z-10 text-center text-[11px] tracking-widest text-white/35">
                    CENDEKIAWAN · BERAKHLAK · MULIA
                </p>
            </div>

            {/* ============== Area formulir ============== */}
            <div className="flex flex-col items-center justify-center gap-6 px-5 py-10 sm:px-8 sm:py-12">
                {/* Brand ringkas untuk mobile */}
                <Link
                    href={home()}
                    className="flex items-center gap-2.5 lg:hidden"
                >
                    <div className="flex size-10 items-center justify-center rounded-md bg-brand p-2">
                        <BrandLogo className="size-full" />
                    </div>
                    <div className="leading-tight text-slate-900">
                        <p className="text-sm font-bold tracking-wide">
                            {name}
                        </p>
                        <p className="text-[11px] text-slate-500">
                            Platform Digital Perkaderan IMM UINSA
                        </p>
                    </div>
                </Link>

                <div className="w-full max-w-md">
                    <div className="rounded-xl border border-slate-200 bg-white p-6 shadow-[0_1px_3px_rgba(15,23,42,0.06)] sm:p-8">
                        <div className="mb-7">
                            <div className="mb-4 h-0.5 w-8 bg-gold" />
                            <h1 className="text-[22px] leading-tight font-extrabold tracking-tight text-slate-900">
                                {title}
                            </h1>
                            {description && (
                                <p className="mt-2 text-sm leading-relaxed text-slate-500">
                                    {description}
                                </p>
                            )}
                        </div>
                        {children}
                    </div>

                    <p className="mt-6 flex flex-wrap items-center justify-center gap-x-1.5 gap-y-1 text-center text-xs text-slate-500">
                        <span>Butuh bantuan?</span>
                        <a
                            href="mailto:imm.uinsa@gmail.com"
                            className="font-semibold text-brand underline decoration-brand/30 underline-offset-4 transition hover:decoration-brand"
                        >
                            Hubungi pengurus komisariat
                        </a>
                    </p>

                    <p className="mt-2 text-center text-[11px] leading-relaxed text-slate-400">
                        {name} — Platform Digital Perkaderan IMM UIN Sunan Ampel
                        Surabaya
                    </p>
                </div>
            </div>
        </div>
    );
}
