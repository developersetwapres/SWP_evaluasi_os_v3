import { Head, router } from '@inertiajs/react';
import { Search } from 'lucide-react';
import { useEffect, useState } from 'react';
import { ServerPagination } from '@/components/penilaian/server-pagination';
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import AppLayoutAdministrator from '@/layouts/app-layout-administrator';
import { dashboard } from '@/routes';
import { indexAdministrator } from '@/routes/user';
import type { User } from '@/types';
import type { BreadcrumbItem } from '@/types/navigation';
import type { PaginatedData } from '@/types/pagination';

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

type UserPageProps = {
    users: PaginatedData<User>;
    filters: { search?: string | null };
};

export default function UserPage({ users, filters = {} }: UserPageProps) {
    const [searchTerm, setSearchTerm] = useState(filters.search ?? '');

    const queryParameters = (page = 1) => ({
        page,
        ...(searchTerm ? { search: searchTerm } : {}),
    });

    const changePage = (page: number) => {
        router.get(indexAdministrator(), queryParameters(page), {
            only: ['users', 'filters'],
            preserveScroll: true,
        });
    };

    useEffect(() => {
        if (searchTerm === (filters.search ?? '')) {
            return;
        }

        const timeout = window.setTimeout(() => {
            router.get(
                indexAdministrator(),
                {
                    page: 1,
                    ...(searchTerm ? { search: searchTerm } : {}),
                },
                {
                    only: ['users', 'filters'],
                    preserveScroll: true,
                    replace: true,
                },
            );
        }, 300);

        return () => window.clearTimeout(timeout);
    }, [filters.search, searchTerm]);

    return (
        <AppLayoutAdministrator breadcrumbs={breadcrumbs}>
            <Head title="User Management" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl bg-linear-to-br from-white to-blue-100 p-4">
                <Card className="gap-0">
                    <CardHeader>
                        <div className="mb-4 flex flex-col gap-4 md:flex-row md:items-center">
                            <div className="flex w-full max-w-sm items-center space-x-2">
                                <Search className="h-4 w-4 text-gray-400" />
                                <Input
                                    placeholder="Cari nama, NIP, atau email..."
                                    value={searchTerm}
                                    onChange={(event) =>
                                        setSearchTerm(event.target.value)
                                    }
                                    className="w-full"
                                />
                            </div>
                        </div>
                    </CardHeader>

                    <CardContent>
                        <div className="rounded-md border">
                            <Table>
                                <TableHeader>
                                    <TableRow>
                                        <TableHead>No</TableHead>
                                        <TableHead>NIP/ NRP</TableHead>
                                        <TableHead>
                                            Nama Pegawai/ Outsourcing
                                        </TableHead>
                                        <TableHead>Email</TableHead>
                                        <TableHead>Role</TableHead>
                                        <TableHead className="text-right">
                                            Aksi
                                        </TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    {users.data.length === 0 ? (
                                        <TableRow>
                                            <TableCell
                                                colSpan={6}
                                                className="py-4 text-center text-gray-500"
                                            >
                                                Tidak ada pengguna yang
                                                ditemukan
                                            </TableCell>
                                        </TableRow>
                                    ) : (
                                        users.data.map((user, index) => (
                                            <TableRow key={user.id}>
                                                <TableCell>
                                                    {(users.from ?? 1) + index}
                                                </TableCell>
                                                <TableCell>
                                                    {user.nip}
                                                </TableCell>
                                                <TableCell>
                                                    {user.userable?.name ?? '-'}
                                                </TableCell>
                                                <TableCell>
                                                    {user.email}
                                                </TableCell>
                                                <TableCell>
                                                    {user.role?.join(', ') ??
                                                        '-'}
                                                </TableCell>
                                                <TableCell className="text-right"></TableCell>
                                            </TableRow>
                                        ))
                                    )}
                                </TableBody>
                            </Table>
                        </div>

                        <ServerPagination
                            meta={users}
                            onPageChange={changePage}
                        />
                    </CardContent>
                </Card>
            </div>
        </AppLayoutAdministrator>
    );
}
