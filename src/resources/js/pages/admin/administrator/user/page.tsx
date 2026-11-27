import AppLayoutAdministrator from '@/layouts/app-layout-administrator';
import { dashboard } from '@/routes';
import { indexAdministrator } from '@/routes/user';
import type { User } from '@/types';
import { Head, router } from '@inertiajs/react';
import { BreadcrumbItem } from '@/types/navigation';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';

import { Card, CardContent, CardHeader } from '@/components/ui/card';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Search } from 'lucide-react';
import { Input } from '@/components/ui/input';
import { ServerPagination } from '@/components/penilaian/server-pagination';
import { useState } from 'react';
import { PaginatedData } from '@/types/pagination';

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
    filters: any;
};

export default function UserPage({ users, filters = {} }: UserPageProps) {
    console.log(users);

    const [searchTerm, setSearchTerm] = useState(filters.search ?? '');
    const [statusFilter, setStatusFilter] = useState(filters.status ?? 'all');

    const queryParameters = (page = 1) => ({
        page,
        ...(searchTerm ? { search: searchTerm } : {}),
        ...(statusFilter !== 'all' ? { status: statusFilter } : {}),
    });

    const changePage = (page: number) => {
        router.get(indexAdministrator(), queryParameters(page), {
            only: ['bookingRooms'],
            preserveScroll: true,
            preserveState: true,
        });
    };

    return (
        <AppLayoutAdministrator breadcrumbs={breadcrumbs}>
            <Head title="Pemesanan Ruang Rapat" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl bg-linear-to-br from-white to-blue-100 p-4">
                <Card className="gap-0">
                    <CardHeader>
                        <div className="mb-4 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                            <div className="flex w-full max-w-sm items-center space-x-2">
                                <Search className="h-4 w-4 text-gray-400" />
                                <Input
                                    placeholder="Cari nama pengaju, kegiatan, atau kode pemesanan..."
                                    value=""

                                    className="w-full"
                                />
                            </div>
                            <div className="flex items-center space-x-2">
                                <Select value={'statusFilter'}>
                                    <SelectTrigger className="w-45">
                                        <SelectValue placeholder="Filter Status" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="all">
                                            Semua Status
                                        </SelectItem>
                                        <SelectItem value="booked">
                                            Dipesan
                                        </SelectItem>
                                        <SelectItem value="rescheduled">
                                            Dijadwalkan Ulang
                                        </SelectItem>
                                        <SelectItem value="rejected">
                                            Ditolak
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
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
                                                colSpan={8}
                                                className="py-4 text-center text-gray-500"
                                            >
                                                Tidak ada data pemesanan yang
                                                ditemukan
                                            </TableCell>
                                        </TableRow>
                                    ) : (
                                        users.data.map((user, index) => (
                                            <TableRow key={user.id}>
                                                <TableCell>
                                                    {index + 1}
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
                            meta={users.meta}
                            onPageChange={changePage}
                        />
                    </CardContent>
                </Card>
            </div>
        </AppLayoutAdministrator>
    );
}
