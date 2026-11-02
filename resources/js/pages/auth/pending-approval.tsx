import { Head, Link, usePage } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { dashboard, logout } from '@/routes';

type Props = {
    roleLabel: string;
};

export default function PendingApproval({ roleLabel }: Props) {
    const { auth } = usePage().props;

    return (
        <>
            <Head title="Menunggu Persetujuan" />

            <div className="flex flex-col items-center gap-4 text-center">
                <div className="flex size-16 items-center justify-center rounded-full bg-amber-100 text-amber-600">
                    <svg
                        className="size-8"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            strokeLinecap="round"
                            strokeLinejoin="round"
                            strokeWidth={2}
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                    </svg>
                </div>

                <div className="space-y-2">
                    <h2 className="text-lg font-semibold">
                        Akun Menunggu Persetujuan
                    </h2>
                    <p className="text-sm text-muted-foreground">
                        Terima kasih telah mendaftar,{' '}
                        <strong>{auth.user.name}</strong>. Akun Anda dengan
                        peran{' '}
                        <span className="font-semibold text-brand uppercase">
                            {roleLabel}
                        </span>{' '}
                        sedang dalam proses verifikasi dan aktivasi oleh
                        Administrator.
                    </p>
                </div>

                <p className="rounded-lg border border-slate-200 bg-slate-50 p-3 text-xs leading-relaxed text-slate-500">
                    Setelah akun Anda diverifikasi, Anda dapat mengisi biodata
                    profil untuk melanjutkan proses perkaderan.
                </p>

                <div className="flex items-center gap-3">
                    <Link href={dashboard()}>
                        <Button variant="outline" size="sm">
                            Cek Status
                        </Button>
                    </Link>
                    <Link method="post" href={logout()} as="button">
                        <Button size="sm">Keluar (Logout)</Button>
                    </Link>
                </div>
            </div>
        </>
    );
}

PendingApproval.layout = {
    title: 'Menunggu Persetujuan',
    description:
        'Akun Anda sedang menunggu verifikasi dan aktivasi oleh Administrator',
};
