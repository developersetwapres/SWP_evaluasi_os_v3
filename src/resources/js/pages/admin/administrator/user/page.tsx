import { Head, router } from '@inertiajs/react';
import { Check, LoaderCircle, Pencil, Search, Trash2 } from 'lucide-react';
import { useState } from 'react';
import type { FormEvent } from 'react';
import { toast } from 'sonner';
import { ServerPagination } from '@/components/penilaian/server-pagination';
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
} from '@/components/ui/alert-dialog';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
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
import {
    destroyAdministrator,
    indexAdministrator,
    updateAdministrator,
} from '@/routes/user';
import type { User } from '@/types';
import type { BreadcrumbItem } from '@/types/navigation';
import type { PaginatedData } from '@/types/pagination';

const userRoleOptions = ['evaluator', 'administrator', 'operator'] as const;
type UserRole = (typeof userRoleOptions)[number];
type UserTypeFilter = 'all' | 'outsourcing' | 'pegawai';

function isUserTypeFilter(value: string): value is UserTypeFilter {
    return ['all', 'outsourcing', 'pegawai'].includes(value);
}

function isOutsourcingUser(user: User): boolean {
    return user.userable_type?.endsWith('\\Outsourcing') ?? false;
}

function getUserTypeLabel(user: User): string {
    return isOutsourcingUser(user) ? 'Outsourcing' : 'Pegawai';
}

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
    filters: {
        search?: string | null;
        user_type?: UserTypeFilter | null;
    };
};

export default function UserPage({ users, filters = {} }: UserPageProps) {
    const [searchTerm, setSearchTerm] = useState(filters.search ?? '');
    const userTypeFilter = filters.user_type ?? 'all';
    const [editingRoleUserId, setEditingRoleUserId] = useState<number | null>(
        null,
    );
    const [roleDraft, setRoleDraft] = useState<UserRole[]>(['evaluator']);
    const [nipDraft, setNipDraft] = useState('');
    const [savingRoleUserId, setSavingRoleUserId] = useState<number | null>(
        null,
    );
    const [userToDelete, setUserToDelete] = useState<User | null>(null);
    const [deletingUserId, setDeletingUserId] = useState<number | null>(null);

    const visitUsers = (search: string, userType: UserTypeFilter, page = 1) => {
        router.get(
            indexAdministrator(),
            {
                page,
                ...(search.trim() ? { search: search.trim() } : {}),
                ...(userType !== 'all' ? { user_type: userType } : {}),
            },
            {
                only: ['users', 'filters'],
                preserveScroll: true,
                preserveState: true,
            },
        );
    };

    const changePage = (page: number) => {
        visitUsers(filters.search ?? '', userTypeFilter, page);
    };

    const startEditingRole = (user: User) => {
        const selectedRoles = userRoleOptions.filter((role) =>
            user.role?.includes(role),
        );

        setRoleDraft(selectedRoles.length > 0 ? selectedRoles : ['evaluator']);
        setNipDraft(user.nip ?? '');
        setEditingRoleUserId(user.id);
    };

    const toggleRole = (role: UserRole, checked: boolean) => {
        setRoleDraft((currentRoles) => {
            if (checked && !currentRoles.includes(role)) {
                return userRoleOptions.filter(
                    (option) =>
                        option === role || currentRoles.includes(option),
                );
            }

            if (!checked && currentRoles.length > 1) {
                return currentRoles.filter(
                    (currentRole) => currentRole !== role,
                );
            }

            return currentRoles;
        });
    };

    const saveRole = (user: User) => {
        if (
            editingRoleUserId !== user.id ||
            savingRoleUserId !== null ||
            roleDraft.length === 0
        ) {
            return;
        }

        setSavingRoleUserId(user.id);
        router.put(
            updateAdministrator(user.id).url,
            {
                role: roleDraft,
                ...(isOutsourcingUser(user) ? { nip: nipDraft.trim() } : {}),
            },
            {
                only: ['users', 'filters'],
                preserveScroll: true,
                onSuccess: () => {
                    setEditingRoleUserId(null);

                    toast.success(
                        `Data user ${user.userable?.name ?? ''} berhasil diperbarui.`,
                    );
                },
                onError: (errors) => {
                    toast.error(
                        errors.nip ??
                            errors.role ??
                            'Data user gagal diperbarui. Periksa kembali lalu coba lagi.',
                    );
                },
                onHttpException: (response) => {
                    toast.error(
                        `Role user gagal diperbarui (HTTP ${response.status}).`,
                    );

                    return true;
                },
                onNetworkError: () => {
                    toast.error('Koneksi gagal. Role user belum diperbarui.');
                },
                onFinish: () => setSavingRoleUserId(null),
            },
        );
    };

    const submitSearch = (event: FormEvent<HTMLFormElement>) => {
        event.preventDefault();
        visitUsers(searchTerm, userTypeFilter);
    };

    const confirmDeleteUser = () => {
        if (!userToDelete || deletingUserId !== null) {
            return;
        }

        const user = userToDelete;
        setDeletingUserId(user.id);
        router.delete(destroyAdministrator(user.id).url, {
            only: ['users', 'filters'],
            preserveScroll: true,
            onSuccess: () => {
                setUserToDelete(null);

                toast.success(
                    `User ${user.userable?.name ?? ''} berhasil dihapus.`,
                );
            },
            onError: () => {
                toast.error('User gagal dihapus. Silakan coba lagi.');
            },
            onHttpException: (response) => {
                toast.error(`User gagal dihapus (HTTP ${response.status}).`);

                return true;
            },
            onNetworkError: () => {
                toast.error('Koneksi gagal. User belum dihapus.');
            },
            onFinish: () => setDeletingUserId(null),
        });
    };

    return (
        <AppLayoutAdministrator breadcrumbs={breadcrumbs}>
            <Head title="User Management" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl bg-linear-to-br from-white to-blue-100 p-4">
                <Card className="gap-0">
                    <CardHeader>
                        <form
                            className="mb-4 flex flex-col gap-3 md:flex-row md:items-center md:justify-between"
                            onSubmit={submitSearch}
                        >
                            <div className="flex w-full max-w-lg items-center gap-2">
                                <Input
                                    placeholder="Cari nama, NIP, atau email..."
                                    value={searchTerm}
                                    onChange={(event) =>
                                        setSearchTerm(event.target.value)
                                    }
                                    className="min-w-0 flex-1 bg-white"
                                />
                                <Button type="submit" className="gap-2">
                                    <Search className="h-4 w-4" />
                                    Cari
                                </Button>
                            </div>
                            <div className="flex items-center gap-2">
                                <span className="text-sm font-medium text-muted-foreground">
                                    Jenis user
                                </span>
                                <Select
                                    value={userTypeFilter}
                                    onValueChange={(value) => {
                                        if (isUserTypeFilter(value)) {
                                            visitUsers(
                                                filters.search ?? '',
                                                value,
                                            );
                                        }
                                    }}
                                >
                                    <SelectTrigger className="w-44 bg-white">
                                        <SelectValue placeholder="Semua jenis" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="all">
                                            Semua jenis
                                        </SelectItem>
                                        <SelectItem value="outsourcing">
                                            Outsourcing
                                        </SelectItem>
                                        <SelectItem value="pegawai">
                                            Pegawai
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>
                        </form>
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
                                        <TableHead>Jenis user</TableHead>
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
                                                colSpan={7}
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
                                                    {editingRoleUserId ===
                                                        user.id &&
                                                    isOutsourcingUser(user) ? (
                                                        <Input
                                                            value={nipDraft}
                                                            onChange={(event) =>
                                                                setNipDraft(
                                                                    event.target
                                                                        .value,
                                                                )
                                                            }
                                                            aria-label={`NIP ${user.userable?.name ?? user.email}`}
                                                            className="h-8 w-44 bg-white font-mono"
                                                        />
                                                    ) : (
                                                        <span className="font-mono text-sm">
                                                            {user.nip ?? '-'}
                                                        </span>
                                                    )}
                                                </TableCell>
                                                <TableCell>
                                                    {user.userable?.name ?? '-'}
                                                </TableCell>
                                                <TableCell>
                                                    {user.email ??
                                                        user.nip_sso ??
                                                        '-'}
                                                </TableCell>
                                                <TableCell>
                                                    <span
                                                        className={
                                                            getUserTypeLabel(
                                                                user,
                                                            ) === 'Outsourcing'
                                                                ? 'rounded-full border border-amber-200 bg-amber-50 px-2.5 py-1 text-xs font-medium text-amber-800'
                                                                : 'rounded-full border border-sky-200 bg-sky-50 px-2.5 py-1 text-xs font-medium text-sky-800'
                                                        }
                                                    >
                                                        {getUserTypeLabel(user)}
                                                    </span>
                                                </TableCell>
                                                <TableCell>
                                                    {editingRoleUserId ===
                                                    user.id ? (
                                                        <div className="flex flex-wrap gap-1.5">
                                                            {userRoleOptions.map(
                                                                (role) => (
                                                                    <label
                                                                        key={
                                                                            role
                                                                        }
                                                                        htmlFor={`user-${user.id}-role-${role}`}
                                                                        className="flex cursor-pointer items-center gap-2 rounded-md border bg-white px-2.5 py-1.5 text-xs font-medium text-slate-700 capitalize shadow-sm transition-colors hover:bg-slate-50"
                                                                    >
                                                                        <Checkbox
                                                                            id={`user-${user.id}-role-${role}`}
                                                                            checked={roleDraft.includes(
                                                                                role,
                                                                            )}
                                                                            disabled={
                                                                                roleDraft.length ===
                                                                                    1 &&
                                                                                roleDraft.includes(
                                                                                    role,
                                                                                )
                                                                            }
                                                                            onCheckedChange={(
                                                                                checked,
                                                                            ) =>
                                                                                toggleRole(
                                                                                    role,
                                                                                    checked ===
                                                                                        true,
                                                                                )
                                                                            }
                                                                        />
                                                                        {role}
                                                                    </label>
                                                                ),
                                                            )}
                                                        </div>
                                                    ) : (
                                                        <div className="flex flex-wrap gap-1">
                                                            {user.role
                                                                ?.length ? (
                                                                user.role.map(
                                                                    (role) => (
                                                                        <span
                                                                            key={
                                                                                role
                                                                            }
                                                                            className="rounded-full border border-indigo-100 bg-indigo-50 px-2.5 py-1 text-xs font-medium text-indigo-700 capitalize"
                                                                        >
                                                                            {
                                                                                role
                                                                            }
                                                                        </span>
                                                                    ),
                                                                )
                                                            ) : (
                                                                <span className="text-sm text-muted-foreground">
                                                                    -
                                                                </span>
                                                            )}
                                                        </div>
                                                    )}
                                                </TableCell>
                                                <TableCell className="text-right">
                                                    <div className="flex justify-end gap-1">
                                                        <Button
                                                            type="button"
                                                            variant="ghost"
                                                            size="icon"
                                                            className="h-8 w-8 text-slate-600 hover:bg-indigo-50 hover:text-indigo-700"
                                                            aria-label={`Edit role ${user.userable?.name ?? user.email}`}
                                                            title="Edit role"
                                                            disabled={
                                                                savingRoleUserId !==
                                                                    null ||
                                                                deletingUserId !==
                                                                    null
                                                            }
                                                            onClick={() =>
                                                                startEditingRole(
                                                                    user,
                                                                )
                                                            }
                                                        >
                                                            <Pencil className="h-4 w-4" />
                                                        </Button>
                                                        {editingRoleUserId ===
                                                            user.id && (
                                                            <Button
                                                                type="button"
                                                                variant="ghost"
                                                                size="icon"
                                                                className="h-8 w-8 text-emerald-700 hover:bg-emerald-50 hover:text-emerald-800"
                                                                aria-label="Simpan perubahan"
                                                                title="Simpan perubahan"
                                                                disabled={
                                                                    savingRoleUserId !==
                                                                        null ||
                                                                    deletingUserId !==
                                                                        null
                                                                }
                                                                onClick={() =>
                                                                    saveRole(
                                                                        user,
                                                                    )
                                                                }
                                                            >
                                                                {savingRoleUserId ===
                                                                user.id ? (
                                                                    <LoaderCircle className="h-4 w-4 animate-spin" />
                                                                ) : (
                                                                    <Check className="h-4 w-4" />
                                                                )}
                                                            </Button>
                                                        )}
                                                        <Button
                                                            type="button"
                                                            variant="ghost"
                                                            size="icon"
                                                            className="h-8 w-8 text-rose-600 hover:bg-rose-50 hover:text-rose-700"
                                                            aria-label={`Hapus ${user.userable?.name ?? user.email}`}
                                                            title="Hapus user"
                                                            disabled={
                                                                savingRoleUserId !==
                                                                    null ||
                                                                deletingUserId !==
                                                                    null
                                                            }
                                                            onClick={() =>
                                                                setUserToDelete(
                                                                    user,
                                                                )
                                                            }
                                                        >
                                                            <Trash2 className="h-4 w-4" />
                                                        </Button>
                                                    </div>
                                                </TableCell>
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
            <AlertDialog
                open={userToDelete !== null}
                onOpenChange={(open) => {
                    if (!open && deletingUserId === null) {
                        setUserToDelete(null);
                    }
                }}
            >
                <AlertDialogContent>
                    <AlertDialogHeader>
                        <AlertDialogTitle>Hapus user ini?</AlertDialogTitle>
                        <AlertDialogDescription>
                            Akun {userToDelete?.userable?.name ?? 'user'} dan
                            data{' '}
                            {userToDelete
                                ? getUserTypeLabel(userToDelete).toLowerCase()
                                : 'terkait'}{' '}
                            terkait akan dihapus permanen. Penugasan terkait
                            juga dapat ikut terhapus. Tindakan ini tidak dapat
                            dibatalkan.
                        </AlertDialogDescription>
                    </AlertDialogHeader>
                    <AlertDialogFooter>
                        <AlertDialogCancel disabled={deletingUserId !== null}>
                            Batal
                        </AlertDialogCancel>
                        <AlertDialogAction
                            className="gap-2"
                            disabled={deletingUserId !== null}
                            onClick={(event) => {
                                event.preventDefault();
                                confirmDeleteUser();
                            }}
                        >
                            {deletingUserId !== null ? (
                                <LoaderCircle className="h-4 w-4 animate-spin" />
                            ) : (
                                <Trash2 className="h-4 w-4" />
                            )}
                            Hapus permanen
                        </AlertDialogAction>
                    </AlertDialogFooter>
                </AlertDialogContent>
            </AlertDialog>
        </AppLayoutAdministrator>
    );
}
