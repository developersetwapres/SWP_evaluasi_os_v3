'use client';

import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import AdminLayout from '@/layouts/app/app-adminkmz-layout';
import { cn } from '@/lib/utils';
import {
    store as storeOutsourcing,
    update as updateOutsourcing,
} from '@/routes/outsourcing';
import {
    store as storePegawai,
    update as updatePegawai,
} from '@/routes/pegawai';
import { tempImage } from '@/routes/upload';
import { index } from '@/routes/user';
import { Link, router, usePage } from '@inertiajs/react';
import {
    Briefcase,
    Building,
    Calendar,
    CheckCircle,
    Clock,
    Edit,
    Eye,
    EyeOff,
    HashIcon,
    Mail,
    Plus,
    Search,
    Star,
    Trash2,
    Upload,
    User,
    User2,
    UserCog,
    Users,
} from 'lucide-react';
import { useEffect, useState } from 'react';

export default function UserManagement({
    initialUsers,
    totalOutsourcing,
    outsourcingAktif,
    outsourcingNonAktif,
    totalPegawai,
    pegawaiMenilai,
    biros,
    pegawaiTidakMenilai,
    jabatans,
}: any) {
    const { flash } = usePage().props;
    const imageUrl = flash?.pathTemp ?? '';

    const [users, setUsers] = useState(initialUsers);
    const [selectedUser, setSelectedUser] = useState<string | null>(null);
    const [searchTerm, setSearchTerm] = useState('');
    const [isDialogOpen, setIsDialogOpen] = useState(false);
    const [isDialogAsnOpen, setIsDialogAsnOpen] = useState(false);
    const [editingUser, setEditingUser] = useState<any>(null);
    const [showPassword, setShowPassword] = useState(false);
    const [onProcessing, setOnProcessing] = useState(false);
    const [formData, setFormData] = useState({
        name: '',
        email: '',
        jabatan: '',
        nip: '',
        unit_kerja: '',
        status: '',
        image: '',
        password: '',
    });

    useEffect(() => {
        setUsers(initialUsers);
    }, [initialUsers]);

    useEffect(() => {
        if (imageUrl) {
            setFormData((prev) => ({
                ...prev,
                image: imageUrl,
            }));
        }
    }, [imageUrl]);

    const filteredUsers = users?.filter((user: any) => {
        const matchesSearch =
            user.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
            user.email?.toLowerCase().includes(searchTerm.toLowerCase()) ||
            user.jabatan?.toLowerCase().includes(searchTerm.toLowerCase());

        const matchesRole =
            selectedUser == null || user.is_active == selectedUser;
        return matchesSearch && matchesRole;
    });

    const handleAdd = () => {
        setEditingUser(null);
        setFormData({
            name: '',
            email: '',
            jabatan: '',
            unit_kerja: '',
            nip: '',
            status: '',
            image: '',
            password: '',
        });
        setIsDialogOpen(true);
    };

    console.log(formData);

    const handleEdit = (user: any) => {
        setEditingUser(user);
        setFormData({
            name: user.name,
            email: user.email,
            jabatan: user.id_jabatan,
            unit_kerja: user.kode_biro,
            nip: user.nip,
            status: user.is_active?.toString(),
            image: user.image,
            password: '',
        });

        if (user.type == 'pegawai') {
            setIsDialogAsnOpen(true);
        } else {
            setIsDialogOpen(true);
        }
    };

    const handleDelete = (id: number) => {
        //
    };

    const handleSave = (type: string) => {
        if (editingUser) {
            const update =
                type == 'os'
                    ? updateOutsourcing.url(editingUser.id)
                    : updatePegawai.url(editingUser.id);

            router.put(update, formData, {
                onSuccess: () => {
                    setIsDialogOpen(false);
                    setIsDialogAsnOpen(false);
                    setOnProcessing(false);

                    // toast({
                    //     title: 'Validasi Berhasil',
                    //     description: 'Data user berhasil diperbarui.',
                    // });
                },
                onError: (err) => {
                    // toast({
                    //     title: 'Validasi gagal',
                    //     description: Object.values(err)[0],
                    //     variant: 'destructive',
                    // });
                    setOnProcessing(false);
                },
            });
        } else {
            const store =
                type == 'os' ? storeOutsourcing.url() : storePegawai.url();

            router.post(store, formData, {
                onSuccess: () => {
                    setIsDialogOpen(false);
                    setIsDialogAsnOpen(false);
                    setOnProcessing(false);

                    // toast({
                    //     title: 'Validasi Berhasil',
                    //     description: 'Data user berhasil ditambahkan.',
                    // });
                },
                onError: (err) => {
                    // toast({
                    //     title: 'Validasi gagal',
                    //     description: Object.values(err)[0],
                    //     variant: 'destructive',
                    // });
                    setOnProcessing(false);
                },
            });
        }
    };

    const toggleUserStatus = (id: number) => {
        const user = users.find((u: any) => u.id === id);

        const newStatus = user?.status === 'active' ? 'inactive' : 'active';

        setUsers(
            users.map((user: any) =>
                user.id === id ? { ...user, status: newStatus } : user,
            ),
        );
    };

    const handleImageUpload = (e: React.ChangeEvent<HTMLInputElement>) => {
        const file = e.target.files?.[0];

        if (file) {
            router.post(
                tempImage.url(),
                { image: file },
                {
                    onSuccess: () => {
                        setFormData({ ...formData, image: imageUrl });
                        console.log('Image uploaded successfully:', imageUrl);
                    },
                    onError: (err) => {
                        console.error('Image upload failed:', err);
                    },
                },
            );
        }
    };

    const { url } = usePage();
    const isOutsourcing = url.startsWith(index.url('outsourcings'));
    const isEvaluator = url.startsWith(index.url('evaluators'));

    return (
        <AdminLayout>
            <div className="space-y-6">
                {/* Header Card */}
                <Card className="bg-linear-to-r from-purple-500 to-indigo-600 text-white">
                    <CardHeader>
                        <CardTitle className="flex items-center space-x-2 text-2xl">
                            <UserCog className="h-6 w-6" />
                            <span>Manajemen User</span>
                        </CardTitle>
                        <CardDescription className="text-purple-100">
                            Kelola user sistem: Administrator, Atasan, Penerima
                            Layanan dan Outsourcing.
                        </CardDescription>
                    </CardHeader>
                </Card>

                <nav className="grid h-10 w-full grid-cols-2 rounded-lg bg-muted p-1">
                    <Link
                        href={index.url('outsourcings')}
                        preserveScroll
                        className={cn(
                            'flex items-center justify-center space-x-2 rounded-md px-3 py-2 text-sm font-medium transition-all',
                            isOutsourcing
                                ? 'bg-background text-foreground shadow-sm'
                                : 'text-muted-foreground hover:bg-background/50 hover:text-foreground',
                        )}
                    >
                        <User2 className="h-4 w-4" />
                        <span className="hidden sm:inline">
                            User Outsourcing
                        </span>
                    </Link>

                    <Link
                        href={index.url('evaluators')}
                        preserveScroll
                        className={cn(
                            'flex items-center justify-center space-x-2 rounded-md px-3 py-2 text-sm font-medium transition-all',
                            isEvaluator
                                ? 'bg-background text-foreground shadow-sm'
                                : 'text-muted-foreground hover:bg-background/50 hover:text-foreground',
                        )}
                    >
                        <User2 className="h-4 w-4" />
                        <span className="hidden sm:inline">User Pegawai</span>
                    </Link>
                </nav>

                {/* Main Content */}
                <Card>
                    <CardHeader>
                        {filteredUsers[0]?.type == 'pegawai' ? (
                            <div className="mb-2 grid grid-cols-1 gap-4 md:grid-cols-3">
                                <Card className="border-l-4 border-l-cyan-500 bg-linear-to-br from-cyan-50 to-white">
                                    <CardHeader className="flex-row items-center justify-between space-y-0 pb-2">
                                        <CardTitle className="text-sm font-medium text-slate-600">
                                            Pegawai Yang Menilai
                                        </CardTitle>
                                        <Calendar className="size-5 text-cyan-500" />
                                    </CardHeader>
                                    <CardContent>
                                        <div className="text-3xl font-bold text-cyan-600">
                                            {pegawaiMenilai}
                                        </div>
                                    </CardContent>
                                </Card>

                                <Card className="border-l-4 border-l-purple-500 bg-linear-to-br from-purple-50 to-white">
                                    <CardHeader className="flex-row items-center justify-between space-y-0 pb-2">
                                        <CardTitle className="text-sm font-medium text-slate-600">
                                            Pegawai Tidak Menilai
                                        </CardTitle>
                                        <Users className="size-5 text-purple-500" />
                                    </CardHeader>
                                    <CardContent>
                                        <div className="text-3xl font-bold text-purple-600">
                                            {pegawaiTidakMenilai}
                                        </div>
                                    </CardContent>
                                </Card>

                                <Card className="border-l-4 border-l-rose-500 bg-linear-to-br from-rose-50 to-white">
                                    <CardHeader className="flex-row items-center justify-between space-y-0 pb-2">
                                        <CardTitle className="text-sm font-medium text-slate-600">
                                            Total Pegawai
                                        </CardTitle>
                                        <Clock className="size-5 text-rose-500" />
                                    </CardHeader>
                                    <CardContent>
                                        <div className="text-3xl font-bold text-rose-600">
                                            {totalPegawai}
                                        </div>
                                    </CardContent>
                                </Card>
                            </div>
                        ) : (
                            <div className="mb-2 grid grid-cols-1 gap-4 md:grid-cols-3">
                                <Card className="border-l-4 border-l-cyan-500 bg-linear-to-br from-cyan-50 to-white">
                                    <CardHeader className="flex-row items-center justify-between space-y-0 pb-2">
                                        <CardTitle className="text-sm font-medium text-slate-600">
                                            Outsourcing Aktif
                                        </CardTitle>
                                        <Calendar className="size-5 text-cyan-500" />
                                    </CardHeader>
                                    <CardContent>
                                        <div className="text-3xl font-bold text-cyan-600">
                                            {outsourcingAktif}
                                        </div>
                                    </CardContent>
                                </Card>

                                <Card className="border-l-4 border-l-purple-500 bg-linear-to-br from-purple-50 to-white">
                                    <CardHeader className="flex-row items-center justify-between space-y-0 pb-2">
                                        <CardTitle className="text-sm font-medium text-slate-600">
                                            Outsourcing Non Aktif
                                        </CardTitle>
                                        <Users className="size-5 text-purple-500" />
                                    </CardHeader>
                                    <CardContent>
                                        <div className="text-3xl font-bold text-purple-600">
                                            {outsourcingNonAktif}
                                        </div>
                                    </CardContent>
                                </Card>

                                <Card className="border-l-4 border-l-rose-500 bg-linear-to-br from-rose-50 to-white">
                                    <CardHeader className="flex-row items-center justify-between space-y-0 pb-2">
                                        <CardTitle className="text-sm font-medium text-slate-600">
                                            Total Outsourcing
                                        </CardTitle>
                                        <Clock className="size-5 text-rose-500" />
                                    </CardHeader>
                                    <CardContent>
                                        <div className="text-3xl font-bold text-rose-600">
                                            {totalOutsourcing}
                                        </div>
                                    </CardContent>
                                </Card>
                            </div>
                        )}
                    </CardHeader>
                    <CardContent>
                        {/* Filters */}
                        <div className="mb-6 flex flex-col gap-4 sm:flex-row">
                            <div className="relative flex-1">
                                <Search className="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 transform text-gray-400" />
                                <Input
                                    placeholder="Cari user..."
                                    value={searchTerm}
                                    onChange={(e) =>
                                        setSearchTerm(e.target.value)
                                    }
                                    className="pl-10"
                                />
                            </div>

                            <Select
                                value={selectedUser || 'Semua Status'}
                                onValueChange={(value) =>
                                    setSelectedUser(
                                        value === 'Semua Status' ? null : value,
                                    )
                                }
                            >
                                <SelectTrigger className="md:w-48">
                                    <SelectValue placeholder="Filter Status" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="Semua Status">
                                        Semua Status
                                    </SelectItem>
                                    <SelectItem value={'1'}>Aktif</SelectItem>
                                    <SelectItem value={'0'}>
                                        Non Aktif
                                    </SelectItem>
                                </SelectContent>
                            </Select>

                            <Button
                                onClick={handleAdd}
                                className="flex items-center space-x-2"
                            >
                                <Plus className="h-4 w-4" />
                                <span>Tambah User Os</span>
                            </Button>
                        </div>

                        {/* Users Grid */}
                        <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                            {filteredUsers?.map((user: any, index: number) => {
                                if (user.type == 'pegawai') {
                                    return (
                                        <Card
                                            key={index}
                                            className="gap-0 overflow-hidden rounded-xl bg-white shadow-lg transition-shadow duration-300 hover:shadow-xl"
                                        >
                                            <CardHeader className="">
                                                <div className="flex items-center gap-4">
                                                    <Avatar className="h-15 w-15">
                                                        <AvatarImage
                                                            src={
                                                                `/storage/${user.image}` ||
                                                                '/placeholder.svg'
                                                            }
                                                            alt={user.name}
                                                        />
                                                        <AvatarFallback>
                                                            {user.name
                                                                .split(' ')
                                                                .map(
                                                                    (n: any) =>
                                                                        n[0],
                                                                )
                                                                .join('')
                                                                .substring(0, 2)
                                                                .toUpperCase()}
                                                        </AvatarFallback>
                                                    </Avatar>

                                                    <div>
                                                        <h2 className="font-semibold text-gray-800">
                                                            {user.name}
                                                        </h2>
                                                        <p className="text-sm text-gray-600">
                                                            NIP: {user.nip}
                                                        </p>
                                                    </div>
                                                </div>
                                            </CardHeader>
                                            <CardContent className="p-6">
                                                <div className="space-y-3">
                                                    <div className="flex items-center gap-2">
                                                        <Briefcase className="h-4 w-4 text-purple-500" />
                                                        <span className="text-sm">
                                                            {user.jabatan}
                                                        </span>
                                                    </div>

                                                    <div className="flex items-center gap-2">
                                                        <Building className="h-4 w-4 text-purple-500" />
                                                        <span className="text-sm">
                                                            {user.biro}
                                                        </span>
                                                    </div>
                                                    <div className="flex items-center gap-2">
                                                        <Star className="h-4 w-4 text-yellow-500" />
                                                        <span className="text-sm font-medium">
                                                            Role :
                                                        </span>
                                                        {user?.role?.map(
                                                            (
                                                                role: string,
                                                                index: number,
                                                            ) => (
                                                                <Badge
                                                                    key={index}
                                                                    variant="outline"
                                                                    className="text-xs"
                                                                >
                                                                    {role}
                                                                </Badge>
                                                            ),
                                                        )}
                                                    </div>
                                                    <div className="flex items-center gap-2">
                                                        <CheckCircle className="h-4 w-4 text-green-500" />
                                                        <span className="text-sm font-medium">
                                                            Jumlah yang dinilai:{' '}
                                                            {user.jumlahDinilai}{' '}
                                                            OS
                                                        </span>
                                                    </div>
                                                </div>
                                                <div className="flex space-x-2 pt-6">
                                                    <Button
                                                        size="sm"
                                                        variant="outline"
                                                        onClick={() =>
                                                            handleEdit(user)
                                                        }
                                                    >
                                                        <Edit className="h-3 w-3" />
                                                    </Button>
                                                    <Button
                                                        size="sm"
                                                        variant="outline"
                                                        onClick={() =>
                                                            handleDelete(
                                                                user.id,
                                                            )
                                                        }
                                                        className="hover:border-red-200 hover:bg-red-50 hover:text-red-600"
                                                    >
                                                        <Trash2 className="h-3 w-3" />
                                                    </Button>
                                                    <div className="ml-2 flex items-center gap-1 rounded-full bg-gray-100 px-2 py-1 text-xs font-medium text-gray-600">
                                                        <HashIcon className="h-3 w-3" />
                                                        <span className="">
                                                            {index + 1}
                                                        </span>
                                                    </div>
                                                </div>
                                            </CardContent>
                                        </Card>
                                    );
                                } else {
                                    return (
                                        <Card
                                            key={index}
                                            className="gap-0 overflow-hidden rounded-xl bg-white shadow-lg transition-shadow duration-300 hover:shadow-xl"
                                        >
                                            <CardHeader className="pb-3">
                                                <div className="flex items-start justify-between">
                                                    <div className="flex items-center space-x-3">
                                                        <Avatar className="h-12 w-12">
                                                            <AvatarImage
                                                                src={
                                                                    `/storage/${user.image}` ||
                                                                    '/placeholder.svg'
                                                                }
                                                                alt={user.name}
                                                            />
                                                            <AvatarFallback>
                                                                {user.name
                                                                    .split(' ')
                                                                    .map(
                                                                        (
                                                                            n: any,
                                                                        ) =>
                                                                            n[0],
                                                                    )
                                                                    .join('')
                                                                    .substring(
                                                                        0,
                                                                        2,
                                                                    )
                                                                    .toUpperCase()}
                                                            </AvatarFallback>
                                                        </Avatar>
                                                        <div>
                                                            <CardTitle className="font-semibold text-gray-800">
                                                                {user.name}
                                                            </CardTitle>
                                                            <p className="mt-0.5 text-xs text-gray-600">
                                                                NRP: {user.nip}
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <Badge
                                                        variant={
                                                            user.status ===
                                                            'active'
                                                                ? 'default'
                                                                : 'secondary'
                                                        }
                                                        className="cursor-pointer"
                                                        onClick={() =>
                                                            toggleUserStatus(
                                                                user.id,
                                                            )
                                                        }
                                                    >
                                                        {user.is_active == 1
                                                            ? 'Aktif'
                                                            : 'Nonaktif'}
                                                    </Badge>
                                                </div>
                                            </CardHeader>
                                            <CardContent className="px-6 pt-6 pb-0.5">
                                                <div className="space-y-3">
                                                    <div className="flex items-center gap-2">
                                                        <Mail className="h-4 w-4 text-purple-500" />
                                                        <span className="text-sm">
                                                            {user.email}
                                                        </span>
                                                    </div>

                                                    <div className="flex items-center gap-2">
                                                        <Briefcase className="h-4 w-4 text-purple-500" />
                                                        <span className="text-sm">
                                                            {user.jabatan}
                                                        </span>
                                                    </div>

                                                    <div className="flex items-center gap-2">
                                                        <Building className="h-4 w-4 text-purple-500" />
                                                        <span className="text-sm">
                                                            {user.biro}
                                                        </span>
                                                    </div>

                                                    <div className="flex items-center gap-2">
                                                        <CheckCircle className="h-4 w-4 text-green-500" />
                                                        <span className="text-sm font-medium">
                                                            Jumlah yang dinilai:{' '}
                                                            {user.jumlahDinilai}{' '}
                                                            OS
                                                        </span>
                                                    </div>
                                                </div>

                                                <div className="flex space-x-2 pt-6">
                                                    <Button
                                                        size="sm"
                                                        variant="outline"
                                                        onClick={() =>
                                                            handleEdit(user)
                                                        }
                                                    >
                                                        <Edit className="h-3 w-3" />
                                                    </Button>
                                                    <Button
                                                        size="sm"
                                                        variant="outline"
                                                        onClick={() =>
                                                            handleDelete(
                                                                user.id,
                                                            )
                                                        }
                                                        className="hover:border-red-200 hover:bg-red-50 hover:text-red-600"
                                                    >
                                                        <Trash2 className="h-3 w-3" />
                                                    </Button>
                                                    <div className="ml-2 flex items-center gap-1 rounded-full bg-gray-100 px-2 py-1 text-xs font-medium text-gray-600">
                                                        <HashIcon className="h-3 w-3" />
                                                        <span className="">
                                                            {index + 1}
                                                        </span>
                                                    </div>
                                                </div>
                                            </CardContent>
                                        </Card>
                                    );
                                }
                            })}
                        </div>

                        {filteredUsers?.length === 0 && (
                            <Card className="py-12 text-center">
                                <CardContent>
                                    <User className="mx-auto mb-4 h-12 w-12 text-gray-400" />
                                    <h3 className="mb-2 text-lg font-medium text-gray-900">
                                        Tidak ada user ditemukan
                                    </h3>
                                    <p className="text-gray-500">
                                        Coba ubah kata kunci pencarian atau
                                        filter
                                    </p>
                                </CardContent>
                            </Card>
                        )}
                    </CardContent>
                </Card>

                {/* Add/Edit Dialog */}
                <Dialog open={isDialogOpen} onOpenChange={setIsDialogOpen}>
                    <DialogContent className="max-h-[80vh] gap-0.5 overflow-y-auto sm:max-w-175">
                        <DialogHeader>
                            <DialogTitle>
                                {editingUser
                                    ? 'Edit User Outsourcing'
                                    : 'Tambah User Outsourcing Baru'}
                            </DialogTitle>
                            <DialogDescription>
                                {editingUser
                                    ? 'Edit informasi user'
                                    : 'Tambahkan user baru ke sistem'}
                            </DialogDescription>
                        </DialogHeader>

                        <div className="grid gap-4 py-4">
                            {/* Image Upload */}
                            <div className="space-y-2">
                                <Label htmlFor="image">Foto Profil</Label>
                                <div className="flex items-center space-x-4">
                                    <Avatar className="h-16 w-16">
                                        <AvatarImage
                                            src={
                                                `/storage/${formData.image}` ||
                                                '/placeholder.svg'
                                            }
                                            alt="Preview"
                                        />
                                        <AvatarFallback>
                                            {formData.name
                                                ? formData.name
                                                      .split(' ')
                                                      .map((n) => n[0])
                                                      .join('')
                                                      .substring(0, 2)
                                                      .toUpperCase()
                                                : 'U'}
                                        </AvatarFallback>
                                    </Avatar>
                                    <div className="flex-1">
                                        <Input
                                            id="image"
                                            type="file"
                                            accept="image/*"
                                            onChange={handleImageUpload}
                                            className="hidden"
                                        />
                                        <Button
                                            type="button"
                                            variant="outline"
                                            onClick={() =>
                                                document
                                                    .getElementById('image')
                                                    ?.click()
                                            }
                                            className="flex items-center space-x-2"
                                        >
                                            <Upload className="h-4 w-4" />
                                            <span>Upload Foto</span>
                                        </Button>
                                    </div>
                                </div>
                            </div>
                            <div className="grid grid-cols-2 gap-4">
                                <div className="space-y-2">
                                    <Label htmlFor="name">Nama Lengkap</Label>
                                    <Input
                                        id="name"
                                        value={formData.name}
                                        onChange={(e) =>
                                            setFormData({
                                                ...formData,
                                                name: e.target.value,
                                            })
                                        }
                                        placeholder="Masukkan nama lengkap"
                                    />
                                </div>
                                <div className="space-y-2">
                                    <Label htmlFor="email">Email</Label>
                                    <Input
                                        id="email"
                                        type="email"
                                        value={formData.email}
                                        onChange={(e) =>
                                            setFormData({
                                                ...formData,
                                                email: e.target.value,
                                            })
                                        }
                                        placeholder="Masukkan email"
                                        disabled={!editingUser}
                                        className={
                                            !editingUser
                                                ? 'border-gray-400 bg-gray-200'
                                                : ''
                                        }
                                    />
                                </div>
                            </div>

                            <div className="grid grid-cols-2 gap-4">
                                <div className="space-y-2">
                                    <Label htmlFor="nip">NIP/ NRP</Label>
                                    <Input
                                        id="nip"
                                        value={formData.nip}
                                        onChange={(e) =>
                                            setFormData({
                                                ...formData,
                                                nip: e.target.value,
                                            })
                                        }
                                        disabled={editingUser}
                                        placeholder="Masukkan NIP/NRP"
                                    />
                                </div>

                                <div className="space-y-2">
                                    <Label htmlFor="unit_kerja">
                                        Unit Kerja
                                    </Label>
                                    <Select
                                        value={formData.unit_kerja}
                                        onValueChange={(value) =>
                                            setFormData({
                                                ...formData,
                                                unit_kerja: value,
                                            })
                                        }
                                    >
                                        <SelectTrigger>
                                            <SelectValue placeholder="Pilih unit kerja" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            {biros.map((biro: any) => (
                                                <SelectItem
                                                    key={biro.id}
                                                    value={biro.kode_biro}
                                                >
                                                    {biro.kode_biro}
                                                    {'_'}
                                                    {biro.nama_biro}
                                                </SelectItem>
                                            ))}
                                        </SelectContent>
                                    </Select>
                                </div>
                            </div>

                            <div className="grid grid-cols-2 gap-4">
                                <div className="space-y-2">
                                    <Label htmlFor="jabatan">Jabatan</Label>
                                    <Select
                                        value={formData.jabatan}
                                        onValueChange={(value) =>
                                            setFormData({
                                                ...formData,
                                                jabatan: value,
                                            })
                                        }
                                    >
                                        <SelectTrigger>
                                            <SelectValue placeholder="Pilih jabatan" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            {jabatans.map((jabatan: any) => (
                                                <SelectItem
                                                    key={jabatan.id}
                                                    value={jabatan.id}
                                                >
                                                    {jabatan.nama_jabatan}
                                                </SelectItem>
                                            ))}
                                        </SelectContent>
                                    </Select>
                                </div>

                                <div className="space-y-2">
                                    <Label htmlFor="status">Status</Label>
                                    <Select
                                        value={formData.status}
                                        onValueChange={(value) =>
                                            setFormData({
                                                ...formData,
                                                status: value,
                                            })
                                        }
                                    >
                                        <SelectTrigger>
                                            <SelectValue placeholder="Pilih status os" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem value={String(1)}>
                                                Aktif
                                            </SelectItem>
                                            <SelectItem value={String(0)}>
                                                Tidak Aktif
                                            </SelectItem>
                                        </SelectContent>
                                    </Select>
                                </div>
                            </div>
                            <div className="grid grid-cols-2 gap-4">
                                <div className="space-y-2">
                                    <Label htmlFor="password">Password</Label>
                                    <div className="relative">
                                        <Input
                                            id="password"
                                            type={
                                                showPassword
                                                    ? 'text'
                                                    : 'password'
                                            }
                                            value={formData.password}
                                            onChange={(e) =>
                                                setFormData({
                                                    ...formData,
                                                    password: e.target.value,
                                                })
                                            }
                                            placeholder={
                                                editingUser
                                                    ? 'Kosongkan jika tidak ingin mengubah'
                                                    : 'Masukkan password'
                                            }
                                            disabled={!editingUser}
                                            className={
                                                !editingUser
                                                    ? 'border-gray-400 bg-gray-200'
                                                    : ''
                                            }
                                        />
                                        <Button
                                            type="button"
                                            variant="ghost"
                                            size="icon"
                                            className="absolute top-0 right-0 h-full px-3 py-2 hover:bg-transparent"
                                            onClick={() =>
                                                setShowPassword(!showPassword)
                                            }
                                        >
                                            {showPassword ? (
                                                <EyeOff className="h-4 w-4" />
                                            ) : (
                                                <Eye className="h-4 w-4" />
                                            )}
                                        </Button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <DialogFooter>
                            <Button
                                variant="outline"
                                onClick={() => {
                                    (setIsDialogOpen(false),
                                        setOnProcessing(false));
                                }}
                            >
                                Batal
                            </Button>
                            <Button
                                onClick={() => {
                                    (handleSave('os'), setOnProcessing(true));
                                }}
                                disabled={onProcessing}
                            >
                                {onProcessing
                                    ? 'Sedang memproses...'
                                    : editingUser
                                      ? 'Update'
                                      : 'Simpan'}
                            </Button>
                        </DialogFooter>
                    </DialogContent>
                </Dialog>

                <Dialog
                    open={isDialogAsnOpen}
                    onOpenChange={setIsDialogAsnOpen}
                >
                    <DialogContent className="max-h-[80vh] gap-0.5 overflow-y-auto sm:max-w-175">
                        <DialogHeader>
                            <DialogTitle>'Edit User Pegawai</DialogTitle>
                            <DialogDescription>
                                Edit informasi user pegawai
                            </DialogDescription>
                        </DialogHeader>

                        <div className="grid gap-4 py-4">
                            {/* Image Upload */}
                            <div className="space-y-2">
                                <Label htmlFor="image">Foto Profil</Label>
                                <div className="flex items-center space-x-4">
                                    <Avatar className="h-16 w-16">
                                        <AvatarImage
                                            src={
                                                `/storage/${formData.image}` ||
                                                '/placeholder.svg'
                                            }
                                            alt="Preview"
                                        />
                                        <AvatarFallback>
                                            {formData.name
                                                ? formData.name
                                                      .split(' ')
                                                      .map((n) => n[0])
                                                      .join('')
                                                      .substring(0, 2)
                                                      .toUpperCase()
                                                : 'U'}
                                        </AvatarFallback>
                                    </Avatar>
                                    <div className="flex-1">
                                        <Input
                                            id="image"
                                            type="file"
                                            accept="image/*"
                                            onChange={handleImageUpload}
                                            className="hidden"
                                        />
                                        <Button
                                            type="button"
                                            variant="outline"
                                            onClick={() =>
                                                document
                                                    .getElementById('image')
                                                    ?.click()
                                            }
                                            className="flex items-center space-x-2"
                                        >
                                            <Upload className="h-4 w-4" />
                                            <span>Upload Foto</span>
                                        </Button>
                                    </div>
                                </div>
                            </div>
                            <div className="grid grid-cols-2 gap-4">
                                <div className="space-y-2">
                                    <Label htmlFor="name">Nama Lengkap</Label>
                                    <Input
                                        id="name"
                                        value={formData.name}
                                        onChange={(e) =>
                                            setFormData({
                                                ...formData,
                                                name: e.target.value,
                                            })
                                        }
                                        placeholder="Masukkan nama lengkap"
                                    />
                                </div>
                                <div className="space-y-2">
                                    <Label htmlFor="nip">NIP</Label>
                                    <Input
                                        id="nip"
                                        value={formData.nip}
                                        onChange={(e) =>
                                            setFormData({
                                                ...formData,
                                                nip: e.target.value,
                                            })
                                        }
                                        disabled={editingUser}
                                        placeholder="Masukkan NIP/NRP"
                                        className={
                                            editingUser
                                                ? 'border-gray-400 bg-gray-200'
                                                : ''
                                        }
                                    />
                                </div>
                            </div>

                            <div className="space-y-2">
                                <Label htmlFor="jabatan">Jabatan</Label>
                                <Input
                                    id="jabatan"
                                    value={formData.jabatan}
                                    onChange={(e) =>
                                        setFormData({
                                            ...formData,
                                            jabatan: e.target.value,
                                        })
                                    }
                                    placeholder="Masukkan jabatan"
                                />
                            </div>

                            <div className="space-y-2">
                                <Label htmlFor="unit_kerja">Unit Kerja</Label>
                                <Select
                                    value={formData.unit_kerja}
                                    onValueChange={(value) =>
                                        setFormData({
                                            ...formData,
                                            unit_kerja: value,
                                        })
                                    }
                                >
                                    <SelectTrigger>
                                        <SelectValue placeholder="Pilih unit kerja" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        {biros.map((biro: any) => (
                                            <SelectItem
                                                key={biro.id}
                                                value={biro.kode_biro}
                                            >
                                                {biro.kode_biro}
                                                {'_'}
                                                {biro.nama_biro}
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                            </div>
                        </div>

                        <DialogFooter>
                            <Button
                                variant="outline"
                                onClick={() => {
                                    (setIsDialogAsnOpen(false),
                                        setOnProcessing(false));
                                }}
                            >
                                Batal
                            </Button>
                            <Button
                                onClick={() => {
                                    (handleSave('asn'), setOnProcessing(true));
                                }}
                                disabled={onProcessing}
                            >
                                {onProcessing
                                    ? 'Sedang memproses...'
                                    : 'Simpan'}
                            </Button>
                        </DialogFooter>
                    </DialogContent>
                </Dialog>
            </div>
        </AdminLayout>
    );
}
