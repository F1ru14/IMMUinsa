import { useState } from 'react';
import AppLogoIcon from '@/components/app-logo-icon';

/**
 * Logo resmi IMM UINSA.
 * Taruh berkas logo di public/images/logo-imm-uinsa.png
 * (atau ubah LOGO_SRC di bawah bila formatnya SVG/WEBP).
 * Selama berkas belum ada, mark placeholder bawaan yang ditampilkan.
 */
const LOGO_SRC = '/images/logo-imm-uinsa.png';

type Props = {
    className?: string;
};

export default function BrandLogo({ className = 'size-9' }: Props) {
    const [failed, setFailed] = useState(false);

    if (failed) {
        return <AppLogoIcon className={`${className} fill-current`} />;
    }

    return (
        <img
            src={LOGO_SRC}
            alt="Logo IMM UINSA"
            className={`${className} object-contain`}
            onError={() => setFailed(true)}
        />
    );
}
