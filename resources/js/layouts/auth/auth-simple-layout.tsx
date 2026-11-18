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
            {/* Panel brand */}
            <div className="relative hidden flex-col justify-between bg-[#59040C] p-10 text-white lg:flex">
                <Link href={home()} className="flex items-center gap-3">
                    <BrandLogo className="h-8 w-auto" />
                    <div className="leading-tight">
                        <p className="text-sm font-semibold">{name}</p>
                        <p className="text-xs text-white/55">
                            Platform Digital Perkaderan IMM UINSA
                        </p>
                    </div>
                </Link>

                <div className="max-w-md">
                    <p className="text-xs font-semibold tracking-[0.18em] text-amber-300 uppercase">
                        Komisariat UIN Sunan Ampel
                    </p>
                    <h2 className="mt-4 text-3xl font-bold tracking-tight">
                        Cendekiawan
                        <span className="block text-amber-300">
                            berakhlak mulia
                        </span>
                    </h2>
                    <p className="mt-4 text-sm leading-relaxed text-white/65">
                        Portal perkaderan berjenjang: verifikasi komisariat,
                        penugasan nyata, evaluasi fasilitator, hingga mentoring
                        senior persyarikatan.
                    </p>
                    <div className="mt-6 flex gap-4 text-xs text-white/55">
                        {brandValues.map((value) => (
                            <span key={value}>{value}</span>
                        ))}
                    </div>
                </div>

                <p className="text-xs text-white/35">
                    Cendekiawan · Berakhlak · Mulia
                </p>
            </div>

            {/* Form area */}
            <div className="flex flex-col items-center justify-center gap-6 px-5 py-10 sm:px-8 sm:py-12">
                {/* Compact brand for mobile */}
                <Link
                    href={home()}
                    className="flex items-center gap-2.5 lg:hidden"
                >
                    <span className="leading-tight text-slate-900">
                        <span className="block text-sm font-semibold">
                            {name}
                        </span>
                        <span className="block text-xs text-slate-500">
                            Perkaderan IMM UINSA
                        </span>
                    </span>
                </Link>

                <div className="w-full max-w-md">
                    <div className="rounded-lg border border-slate-200 bg-white p-6 sm:p-8">
                        <div className="mb-7 border-b border-slate-100 pb-5">
                            <h1 className="text-[22px] leading-tight font-bold tracking-tight text-slate-900">
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

                    <p className="mt-6 text-center text-xs text-slate-500">
                        Butuh bantuan?{' '}
                        <a
                            href="mailto:imm.uinsa@gmail.com"
                            className="font-semibold text-brand underline decoration-brand/30 underline-offset-4 transition hover:decoration-brand"
                        >
                            Hubungi pengurus komisariat
                        </a>
                    </p>
                </div>
            </div>
        </div>
    );
}
