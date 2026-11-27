import AppLayoutAdministrator from '@/layouts/app-layout';
import { dashboard } from '@/routes';
import { indexAdministrator } from '@/routes/user';
import { BreadcrumbItem } from '@/types/navigation';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
    },
    {
        title: 'Administrator',
        href: '#',
    },
    {
        title: 'User Management',
        href: indexAdministrator(),
    },
];

export default function UserPage() {
    return (
        <AppLayoutAdministrator breadcrumbs={breadcrumbs}>
            <div className="flex flex-col gap-4">
                <h1 className="text-2xl font-bold">User Management</h1>
                <p>Manage users and their permissions.</p>
            </div>
        </AppLayoutAdministrator>
    );
}
