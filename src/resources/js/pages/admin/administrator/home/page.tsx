import AppLayoutAdministrator from '@/layouts/app-layout-administrator';
import { dashboard, home } from '@/routes';
import { Head } from '@inertiajs/react';
import { BreadcrumbItem } from '@/types/navigation';

import { Card, CardContent, CardHeader } from '@/components/ui/card';
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
    },
    {
        title: 'Administrator',
        href: home(),
    },
];

export default function UserPage() {
    return (
        <AppLayoutAdministrator breadcrumbs={breadcrumbs}>
            <Head title="Pemesanan Ruang Rapat" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl bg-linear-to-br from-white to-blue-100 p-4">
                <Card className="gap-0">
                    <h1>Dashboard Administrator</h1>
                </Card>
            </div>
        </AppLayoutAdministrator>
    );
}
