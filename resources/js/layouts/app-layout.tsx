import AppLayoutTemplate from '@/layouts/app/app-adminkmz-layout';
import type { BreadcrumbItem } from '@/types';

export default function AppLayout({
    breadcrumbs = [],
    children,
}: {
    breadcrumbs?: BreadcrumbItem[];
    children: React.ReactNode;
}) {
    return <AppLayoutTemplate>{children}</AppLayoutTemplate>;
}
