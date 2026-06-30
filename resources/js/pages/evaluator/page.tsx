'use client';

import { Badge } from '@/components/ui/badge';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { useMobileNavigation } from '@/hooks/use-mobile-navigation';
import { logout } from '@/routes';
import { create } from '@/routes/penilaian';

import { Head, Link, router, usePage } from '@inertiajs/react';
import {
    Award,
    Briefcase,
    Calendar,
    CheckCircle,
    ClipboardList,
    Clock,
    History,
    LogOut,
    User,
    Users,
} from 'lucide-react';

export default function EvaluatorPage({
    penugasanPeer,
    semesterHistory,
    resultScoreHistory,
    typeUser,
    resultScore,
    siklusAktif,
}: any) {
    const { auth } = usePage().props;
    const user = auth.user;

    const cleanup = useMobileNavigation();

    const handleLogout = () => {
        cleanup();
        router.flushAll();
    };

    return (
        <>
            <Head title="Evaluator" />
            <div className="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
                {/* Header */}
                <header className="border-b bg-white shadow-sm">
                    <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                        <div className="flex items-center justify-between py-4">
                            <div className="flex items-center space-x-3">
                                <div className="rounded-lg bg-indigo-600 p-2">
                                    <ClipboardList className="h-6 w-6 text-white" />
                                </div>
                                <div>
                                    <h1 className="font-bold text-gray-900 md:text-xl">
                                        Penilaian Outsourcing
                                    </h1>
                                    <p className="flex items-center gap-2 text-xs text-gray-500 md:text-sm">
                                        <Calendar className="h-4 w-4 text-gray-400" />
                                        {siklusAktif}
                                    </p>
                                </div>
                            </div>

                            <Link
                                className="inline-flex items-center justify-center gap-2 space-x-2 rounded-md border bg-transparent px-3 py-2 text-sm font-medium whitespace-nowrap hover:border-red-200 hover:bg-red-50 hover:text-red-600"
                                href={logout()}
                                onClick={handleLogout}
                                data-test="logout-button"
                            >
                                <LogOut className="h-4 w-4" />
                                Log out
                            </Link>
                        </div>
                    </div>
                </header>

                <main className="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
                    <div className="space-y-8">
                        {/* User Profile Card - Simplified */}
                        <Card className="bg-linear-to-r from-green-500 to-green-600 py-5 text-white">
                            <CardHeader className="flex flex-row items-center justify-between">
                                {/* KIRI – biodata */}
                                <div className="flex items-center space-x-4">
                                    <div className="rounded-full bg-white/20 p-3">
                                        <img
                                            src={`/storage/${user?.userable?.image}`}
                                            alt={user?.userable?.name}
                                            className="h-19 w-19 rounded-full"
                                        />
                                    </div>

                                    <div>
                                        <CardTitle className="text-lg text-white md:text-4xl">
                                            {user?.userable?.name}
                                        </CardTitle>
                                        <CardDescription className="text-xs text-green-100 md:text-sm">
                                            {user?.is_ldap == '0'
                                                ? user?.userable?.jabatan
                                                      ?.nama_jabatan
                                                : user?.userable?.jabatan}
                                            {' • '}
                                            {user?.userable?.biro?.nama_biro}
                                        </CardDescription>
                                    </div>
                                </div>

                                {/* KANAN – score */}
                                {typeUser === 'outsourcing' && (
                                    <div className="text-right">
                                        <div className="mb-2 text-4xl font-bold md:text-5xl">
                                            {resultScore?.finalTotalScore?.toFixed(
                                                2,
                                            )}
                                        </div>

                                        <Badge
                                            className={`${getScoreBadgeColor(
                                                resultScore?.finalTotalScore,
                                            )} border-2 text-sm md:px-4 md:py-1.5`}
                                        >
                                            {getScoreLabel(
                                                resultScore?.finalTotalScore,
                                            )}
                                        </Badge>

                                        <p className="mt-2 text-xs text-indigo-100 md:text-lg">
                                            Nilai Akhir Penilaian
                                        </p>
                                    </div>
                                )}
                            </CardHeader>
                        </Card>

                        <Card className="border-0 bg-linear-to-br from-blue-50 shadow-md">
                            <CardContent>
                                <div className="mb-6 flex items-center justify-between">
                                    <div className="flex items-center gap-3">
                                        <div className="rounded-lg bg-gray-100 p-2">
                                            <Users className="h-5 w-5 text-gray-600" />
                                        </div>
                                        <div>
                                            <h3 className="text-lg font-bold text-gray-900">
                                                Daftar Pegawai yang Dinilai
                                            </h3>
                                            <p className="text-xs text-gray-500 sm:text-sm">
                                                Pegawai outsourcing yang harus
                                                dinilai
                                            </p>
                                        </div>
                                    </div>

                                    <div className="items-center gap-4 md:flex">
                                        <div className="mb-2 rounded-lg bg-white px-2 py-1 shadow-md md:mb-0 md:px-4 md:py-2">
                                            <div className="text-center">
                                                <div className="text-lg font-bold text-blue-600 md:text-2xl">
                                                    {penugasanPeer?.length}
                                                </div>
                                                <div className="text-xs text-gray-500">
                                                    Total
                                                </div>
                                            </div>
                                        </div>
                                        <div className="mb-2 rounded-lg bg-white px-2 py-1 shadow-md md:mb-0 md:px-4 md:py-2">
                                            <div className="text-center">
                                                <div className="text-lg font-bold text-green-600 md:text-2xl">
                                                    {
                                                        penugasanPeer?.filter(
                                                            (emp: any) =>
                                                                emp.status ===
                                                                'completed',
                                                        ).length
                                                    }
                                                </div>
                                                <div className="text-xs text-gray-500">
                                                    Selesai
                                                </div>
                                            </div>
                                        </div>
                                        {/* NANTI KETIKA SUDAH DIBUAT SAVE PENDING */}
                                        {/* <div className="mb-2 rounded-lg bg-white px-2 py-1 shadow-md md:mb-0 md:px-4 md:py-2">
                                                        <div className="text-center">
                                                            <div className="text-2xl font-bold text-orange-600">
                                                                {penugasanPeer.pending}
                                                            </div>
                                                            <div className="text-xs text-gray-500">
                                                                Pending
                                                            </div>
                                                        </div>
                                                    </div> */}
                                    </div>
                                </div>

                                {/* Creative Employee Cards */}
                                <div className="grid gap-8 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                                    {penugasanPeer?.map((employee: any) => (
                                        <Card
                                            key={employee.id}
                                            className="group relative overflow-hidden border-0 bg-gradient-to-br from-white to-blue-50 pb-2 shadow-lg transition-all duration-300 hover:scale-105 hover:shadow-2xl"
                                        >
                                            {/* Status Badge - Floating */}
                                            <div className="absolute top-4 right-4 z-10">
                                                <Badge
                                                    variant={
                                                        employee.status ===
                                                        'completed'
                                                            ? 'default'
                                                            : 'secondary'
                                                    }
                                                    className={`flex items-center space-x-1 px-3 py-1 shadow-lg ${
                                                        employee.status ===
                                                        'completed'
                                                            ? 'bg-green-500 text-white hover:bg-green-600'
                                                            : 'bg-orange-500 text-white hover:bg-orange-600'
                                                    }`}
                                                >
                                                    {employee.status ===
                                                    'completed' ? (
                                                        <CheckCircle className="h-3 w-3" />
                                                    ) : (
                                                        <Clock className="h-3 w-3" />
                                                    )}
                                                    <span className="text-xs font-medium">
                                                        {employee.status ==
                                                        'completed'
                                                            ? 'Selesai'
                                                            : 'Belum'}
                                                    </span>
                                                </Badge>
                                            </div>

                                            {/* Decorative Background Pattern */}
                                            <div className="absolute inset-0 opacity-5">
                                                <div className="absolute top-0 right-0 h-32 w-32 translate-x-16 -translate-y-16 rounded-full bg-blue-500"></div>
                                                <div className="absolute bottom-0 left-0 h-24 w-24 -translate-x-12 translate-y-12 rounded-full bg-indigo-500"></div>
                                            </div>

                                            <CardContent className="relative z-10 p-8 text-center">
                                                {/* Photo with Decorative Ring */}
                                                <div className="relative mb-4">
                                                    <img
                                                        src={
                                                            `/storage/${employee.outsourcings.image}` ||
                                                            '/placeholder.svg'
                                                        }
                                                        alt={
                                                            employee
                                                                .outsourcings
                                                                .name
                                                        }
                                                        className="mx-auto h-27 w-27 rounded-full border-4 border-white shadow-md transition-transform duration-300 group-hover:scale-110"
                                                    />
                                                </div>

                                                <h3 className="mb-1 overflow-hidden bg-gradient-to-r from-gray-800 to-blue-600 bg-clip-text text-xl font-bold text-ellipsis whitespace-nowrap text-transparent transition-all duration-300 group-hover:from-blue-600 group-hover:to-indigo-600">
                                                    {employee.outsourcings.name}
                                                </h3>

                                                {/* Position with Icon */}
                                                <div className="mb-6 flex items-center justify-center space-x-2">
                                                    <p className="text-sm font-medium text-gray-600">
                                                        {
                                                            employee
                                                                .outsourcings
                                                                .jabatan
                                                        }
                                                    </p>
                                                </div>

                                                <Link
                                                    href={create.url(
                                                        employee.uuid,
                                                    )}
                                                    className={`mb-3 block w-full transform rounded-md py-2 text-sm font-semibold text-white shadow-lg transition-all duration-300 group-hover:scale-105 ${
                                                        employee.status ===
                                                        'completed'
                                                            ? 'bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700'
                                                            : 'bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700'
                                                    }`}
                                                >
                                                    <div className="flex items-center justify-center space-x-2">
                                                        <ClipboardList className="h-4 w-4" />
                                                        <span>
                                                            {employee.status ===
                                                            'completed'
                                                                ? 'Lihat Penilaian'
                                                                : 'Mulai Penilaian'}
                                                        </span>
                                                    </div>
                                                </Link>

                                                <span className="text-xs text-gray-600">
                                                    Nilai sebagai{' '}
                                                    {employee.tipe_penilai?.replace(
                                                        /_/g,
                                                        ' ',
                                                    )}
                                                </span>
                                            </CardContent>

                                            {/* Hover Effect Overlay */}
                                            <div className="pointer-events-none absolute inset-0 bg-gradient-to-t from-blue-600/10 to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>
                                        </Card>
                                    ))}
                                </div>

                                {penugasanPeer?.length === 0 && (
                                    <Card className="border-0 bg-gradient-to-br from-gray-50 to-blue-50 py-16 text-center shadow-lg">
                                        <CardContent>
                                            <div className="relative">
                                                <div className="absolute inset-0 flex items-center justify-center opacity-10">
                                                    <User className="h-32 w-32 text-gray-400" />
                                                </div>
                                                <div className="relative z-10">
                                                    <div className="mx-auto mb-4 w-fit rounded-full bg-white p-4 shadow-lg">
                                                        <User className="h-12 w-12 text-gray-400" />
                                                    </div>
                                                    <h3 className="mb-2 text-xl font-bold text-gray-900">
                                                        {penugasanPeer?.length ===
                                                        0
                                                            ? 'Tidak ada pegawai yang ditugaskan'
                                                            : 'Tidak ada pegawai ditemukan'}
                                                    </h3>
                                                    <p className="mx-auto max-w-md text-gray-500">
                                                        {penugasanPeer?.length ===
                                                        0
                                                            ? 'Hubungi administrator untuk penugasan penilaian pegawai outsourcing'
                                                            : 'Coba ubah kata kunci pencarian Anda atau periksa filter yang digunakan'}
                                                    </p>
                                                </div>
                                            </div>
                                        </CardContent>
                                    </Card>
                                )}
                            </CardContent>
                        </Card>
                    </div>
                </main>
            </div>
        </>
    );
}
