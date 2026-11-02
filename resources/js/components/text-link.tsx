import { Link } from '@inertiajs/react';
import type { ComponentProps } from 'react';
import { cn } from '@/lib/utils';

type Props = ComponentProps<typeof Link>;

export default function TextLink({
    className = '',
    children,
    ...props
}: Props) {
    return (
        <Link
            className={cn(
                'font-semibold text-brand underline decoration-brand/30 underline-offset-4 transition-colors duration-200 ease-out hover:text-brand-dark hover:decoration-brand focus-visible:rounded-sm focus-visible:ring-2 focus-visible:ring-brand/30 focus-visible:outline-none',
                className,
            )}
            {...props}
        >
            {children}
        </Link>
    );
}
