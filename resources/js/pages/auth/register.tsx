import { Form, Head } from '@inertiajs/react';
import { Check } from 'lucide-react';
import { useState } from 'react';
import InputError from '@/components/input-error';
import PasswordInput from '@/components/password-input';
import TextLink from '@/components/text-link';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { cn } from '@/lib/utils';
import { login } from '@/routes';
import { store } from '@/routes/register';

type Props = {
    passwordRules: string;
};

type RoleOption = 'kader' | 'fasilitator';

const roleOptions: Array<{
    value: RoleOption;
    title: string;
    description: string;
}> = [
    {
        value: 'kader',
        title: 'Kader',
        description:
            'Ikuti perjalanan perkaderan: pilih fokus bidang, kerjakan tugas, dan terhubung dengan mentor senior.',
    },
    {
        value: 'fasilitator',
        title: 'Fasilitator',
        description:
            'Pendamping lapangan yang membimbing kader, memeriksa tugas, dan memberi evaluasi berkala.',
    },
];

const fieldClass =
    'h-10 border-slate-300 bg-white shadow-none focus-visible:border-brand focus-visible:ring-brand/25';

const labelClass = 'text-[13px] font-semibold text-slate-700';

export default function Register({ passwordRules }: Props) {
    const [role, setRole] = useState<RoleOption>('kader');

    return (
        <>
            <Head title="Registrasi" />
            <Form
                {...store.form()}
                resetOnSuccess={['password', 'password_confirmation']}
                disableWhileProcessing
                className="flex flex-col gap-6"
            >
                {({ processing, errors }) => (
                    <>
                        <div className="grid gap-6">
                            <div className="grid gap-2.5">
                                <Label className={labelClass}>
                                    Daftar sebagai
                                </Label>
                                <input
                                    type="hidden"
                                    name="role"
                                    value={role}
                                    data-test="role-input"
                                />
                                <div className="grid gap-2">
                                    {roleOptions.map((option) => {
                                        const isSelected =
                                            role === option.value;

                                        return (
                                            <label
                                                key={option.value}
                                                className={cn(
                                                    'flex cursor-pointer items-start gap-3 rounded-lg border p-3.5 transition-colors',
                                                    'has-[:focus-visible]:ring-2 has-[:focus-visible]:ring-brand/30',
                                                    isSelected
                                                        ? 'border-brand bg-brand-soft ring-1 ring-brand/20'
                                                        : 'border-slate-200 bg-white hover:border-slate-300 hover:bg-slate-50',
                                                )}
                                                data-test={`role-option-${option.value}`}
                                            >
                                                <input
                                                    type="radio"
                                                    name="role-selector"
                                                    value={option.value}
                                                    checked={isSelected}
                                                    onChange={() =>
                                                        setRole(option.value)
                                                    }
                                                    className="sr-only"
                                                />
                                                <span
                                                    aria-hidden="true"
                                                    className={cn(
                                                        'mt-0.5 flex size-4 shrink-0 items-center justify-center rounded-full border transition-colors',
                                                        isSelected
                                                            ? 'border-brand bg-brand text-white'
                                                            : 'border-slate-300 bg-white',
                                                    )}
                                                >
                                                    {isSelected && (
                                                        <Check
                                                            className="size-3"
                                                            strokeWidth={3}
                                                        />
                                                    )}
                                                </span>
                                                <span className="grid gap-1">
                                                    <span className="text-sm font-semibold text-slate-900">
                                                        {option.title}
                                                    </span>
                                                    <span className="text-xs leading-relaxed text-slate-500">
                                                        {option.description}
                                                    </span>
                                                </span>
                                            </label>
                                        );
                                    })}
                                    <InputError message={errors.role} />
                                </div>
                                <p className="text-xs leading-relaxed text-slate-400">
                                    Akun Admin dan Mentor hanya dibuat oleh
                                    Administrator melalui seeder/panel Admin.
                                </p>
                            </div>

                            <div className="border-t border-slate-100" />

                            <div className="grid gap-2">
                                <Label
                                    htmlFor="username"
                                    className={labelClass}
                                >
                                    Username
                                </Label>
                                <Input
                                    id="username"
                                    type="text"
                                    required
                                    autoFocus
                                    tabIndex={1}
                                    autoComplete="username"
                                    name="username"
                                    placeholder="huruf, angka, _ atau -"
                                    className={fieldClass}
                                />
                                <p className="text-xs leading-relaxed text-slate-400">
                                    3–30 karakter, tanpa spasi. Nama lengkap
                                    dilengkapi saat pengisian biodata.
                                </p>
                                <InputError message={errors.username} />
                            </div>

                            <div className="grid gap-2">
                                <Label
                                    htmlFor="password"
                                    className={labelClass}
                                >
                                    Kata Sandi
                                </Label>
                                <PasswordInput
                                    id="password"
                                    required
                                    tabIndex={3}
                                    autoComplete="new-password"
                                    name="password"
                                    placeholder="Minimal 8 karakter"
                                    passwordrules={passwordRules}
                                    className={fieldClass}
                                />
                                <InputError message={errors.password} />
                            </div>

                            <div className="grid gap-2">
                                <Label
                                    htmlFor="password_confirmation"
                                    className={labelClass}
                                >
                                    Konfirmasi Kata Sandi
                                </Label>
                                <PasswordInput
                                    id="password_confirmation"
                                    required
                                    tabIndex={4}
                                    autoComplete="new-password"
                                    name="password_confirmation"
                                    placeholder="Ulangi kata sandi"
                                    passwordrules={passwordRules}
                                    className={fieldClass}
                                />
                                <InputError
                                    message={errors.password_confirmation}
                                />
                            </div>

                            <div className="rounded-lg border border-amber-200 bg-amber-50 p-3.5 text-xs leading-relaxed text-amber-900">
                                <strong className="font-semibold">
                                    Menunggu persetujuan.
                                </strong>{' '}
                                Setelah mendaftar, akun Anda akan diaktivasi
                                oleh Administrator komisariat sebelum dapat
                                digunakan.
                            </div>

                            <Button
                                type="submit"
                                className="h-10 w-full border-b-2 border-transparent bg-brand text-sm font-semibold text-white shadow-none transition hover:border-gold hover:bg-brand-dark focus-visible:border-brand focus-visible:ring-brand/40"
                                tabIndex={5}
                                data-test="register-user-button"
                            >
                                {processing && <Spinner />}
                                Daftar Sekarang
                            </Button>
                        </div>

                        <div className="border-t border-slate-100 pt-5 text-center text-sm text-slate-500">
                            Sudah punya akun?{' '}
                            <TextLink href={login()} tabIndex={6}>
                                Masuk
                            </TextLink>
                        </div>
                    </>
                )}
            </Form>
        </>
    );
}

Register.layout = {
    title: 'Registrasi Perkaderan',
    description:
        'Buat username dan kata sandi untuk mendaftar sebagai kader atau fasilitator.',
};
