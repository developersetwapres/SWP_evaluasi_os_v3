'use client';

import { Badge } from '@/components/ui/badge';
import {
    Accordion,
    AccordionContent,
    AccordionItem,
    AccordionTrigger,
} from '@/components/ui/accordion';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
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
    Calendar,
    CheckCircle,
    ClipboardList,
    Clock,
    LogOut,
    History,
    User,
    Users,
    Award,
    Briefcase,
} from 'lucide-react';
import { getScoreColor } from '@/utils/score';

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
            <div className="min-h-screen bg-linear-to-br from-blue-50 to-indigo-100">
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
                                            {user?.userable_type ==
                                            'App\Models\Outsourcing'
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

                        <Tabs className="space-y-6" defaultValue={'current'}>
                            <TabsList className="w-full gap-1.5 bg-white shadow-md">
                                <TabsTrigger
                                    value="current"
                                    className="flex w-full items-center gap-2 rounded-md border bg-gray-100 text-slate-700 transition-all data-[state=active]:border-blue-600 data-[state=active]:bg-gradient-to-r data-[state=active]:from-blue-500 data-[state=active]:to-indigo-600 data-[state=active]:text-white data-[state=inactive]:hover:bg-gradient-to-r data-[state=inactive]:hover:from-gray-300 data-[state=inactive]:hover:to-gray-500 data-[state=inactive]:hover:text-white"
                                >
                                    <Calendar className="h-4 w-4" />
                                    Semester Aktif
                                </TabsTrigger>

                                <TabsTrigger
                                    value="history"
                                    className="flex w-full items-center gap-2 rounded-md border bg-gray-100 text-slate-700 transition-all data-[state=active]:border-blue-600 data-[state=active]:bg-gradient-to-r data-[state=active]:from-blue-500 data-[state=active]:to-indigo-600 data-[state=active]:text-white data-[state=inactive]:hover:bg-gradient-to-r data-[state=inactive]:hover:from-gray-300 data-[state=inactive]:hover:to-gray-500 data-[state=inactive]:hover:text-white"
                                >
                                    <History className="h-4 w-4" />
                                    Riwayat Semester
                                </TabsTrigger>
                            </TabsList>

                            {/* Current Semester Tab */}
                            <TabsContent value="current" className="space-y-6">
                                {typeUser === 'outsourcing' && (
                                    <Card className="border-0 bg-linear-to-br from-blue-50 shadow-md">
                                        <CardContent>
                                            <div className="flex items-center justify-between">
                                                <div className="flex items-center gap-3">
                                                    <div className="rounded-lg bg-gray-100 p-2">
                                                        <Users className="h-5 w-5 text-gray-600" />
                                                    </div>
                                                    <div>
                                                        <h3 className="text-lg font-bold text-gray-900">
                                                            Nilai Saya —
                                                            Semester Aktif
                                                        </h3>
                                                        <p className="text-sm text-gray-500">
                                                            Ringkasan penilaian
                                                            pribadi pada
                                                            semester berjalan
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div className="mt-4 grid gap-2 md:grid-cols-3">
                                                {resultScore.map(
                                                    (
                                                        score: any,
                                                        index: number,
                                                    ) => (
                                                        <Card
                                                            className="gap-2.5 border-l-4 border-l-purple-500 bg-gradient-to-br from-purple-50 to-white px-4 py-7 shadow-md"
                                                            key={index}
                                                        >
                                                            <div className="mb-3 flex items-start justify-between">
                                                                <div className="flex items-center gap-2">
                                                                    <div className="rounded-md bg-indigo-50 p-2">
                                                                        {score.type ===
                                                                        'atasan' ? (
                                                                            <Briefcase className="h-5 w-5 text-indigo-600" />
                                                                        ) : score.type ===
                                                                          'teman_setingkat' ? (
                                                                            <Users className="h-5 w-5 text-indigo-600" />
                                                                        ) : score.type ===
                                                                          'penerima_layanan' ? (
                                                                            <Award className="h-5 w-5 text-indigo-600" />
                                                                        ) : null}
                                                                    </div>
                                                                    <div>
                                                                        <h4 className="text-sm font-semibold text-gray-900">
                                                                            {score.type
                                                                                .replace(
                                                                                    '_',
                                                                                    ' ',
                                                                                )
                                                                                .replace(
                                                                                    /^./,
                                                                                    (
                                                                                        c: string,
                                                                                    ) =>
                                                                                        c.toUpperCase(),
                                                                                )}{' '}
                                                                            (
                                                                            {(
                                                                                score?.bobot *
                                                                                100
                                                                            ).toFixed(
                                                                                0,
                                                                            )}
                                                                            %)
                                                                        </h4>

                                                                        <p className="text-xs text-gray-500">
                                                                            {score.type ===
                                                                            'atasan'
                                                                                ? 'Penilaian oleh atasan langsung'
                                                                                : ''}
                                                                            {score.type ===
                                                                            'teman_setingkat'
                                                                                ? 'Penilaian oleh teman setingkat'
                                                                                : ''}
                                                                            {score.type ===
                                                                            'penerima_layanan'
                                                                                ? 'Penilaian oleh penerima layanan'
                                                                                : ''}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                                <Badge
                                                                    className={`${
                                                                        score.status ===
                                                                        'completed'
                                                                            ? 'bg-green-100 text-green-800'
                                                                            : 'bg-gray-100 text-gray-800'
                                                                    } shadow-sm`}
                                                                >
                                                                    {score.status ===
                                                                    'completed'
                                                                        ? 'Selesai'
                                                                        : 'Belum Menilai'}
                                                                </Badge>
                                                            </div>

                                                            <div className="flex flex-col items-center">
                                                                <div className="mb-6 w-2/3 rounded-lg bg-white p-3 text-center shadow-sm">
                                                                    <div className="text-sm text-gray-600">
                                                                        Score
                                                                        Asli
                                                                    </div>
                                                                    <div
                                                                        className={`text-2xl font-bold ${getScoreColor(score?.averageScore)}`}
                                                                    >
                                                                        {
                                                                            score?.averageScore
                                                                        }
                                                                    </div>
                                                                </div>
                                                                <div className="text-center font-mono text-sm opacity-90">
                                                                    {
                                                                        score?.averageScore
                                                                    }{' '}
                                                                    ×{' '}
                                                                    {(
                                                                        score?.bobot *
                                                                        100
                                                                    ).toFixed(
                                                                        0,
                                                                    )}
                                                                    % =
                                                                </div>

                                                                <div className="text-center text-4xl font-bold text-gray-900">
                                                                    {
                                                                        score.weightedScore
                                                                    }
                                                                </div>
                                                            </div>

                                                            <div className="text-center text-sm text-gray-600">
                                                                {score.notes
                                                                    ? score.notes
                                                                    : score.status ===
                                                                        'completed'
                                                                      ? '"Tidak ada saran dari penilai"'
                                                                      : '"Belum ada catatan"'}
                                                            </div>
                                                        </Card>
                                                    ),
                                                )}
                                            </div>
                                        </CardContent>
                                    </Card>
                                )}
                                <Card className="border-0 bg-linear-to-br from-blue-50 shadow-md">
                                    <CardContent>
                                        <div className="mb-6 flex items-center justify-between">
                                            <div className="flex items-center gap-3">
                                                <div className="rounded-lg bg-gray-100 p-2">
                                                    <Users className="h-5 w-5 text-gray-600" />
                                                </div>
                                                <div>
                                                    <h3 className="text-lg font-bold text-gray-900">
                                                        Daftar Pegawai yang
                                                        Dinilai
                                                    </h3>
                                                    <p className="text-xs text-gray-500 sm:text-sm">
                                                        Pegawai outsourcing yang
                                                        harus dinilai
                                                    </p>
                                                </div>
                                            </div>

                                            <div className="items-center gap-4 md:flex">
                                                <div className="mb-2 rounded-lg bg-white px-2 py-1 shadow-md md:mb-0 md:px-4 md:py-2">
                                                    <div className="text-center">
                                                        <div className="text-lg font-bold text-blue-600 md:text-2xl">
                                                            {
                                                                penugasanPeer?.length
                                                            }
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
                                                                    (
                                                                        emp: any,
                                                                    ) =>
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
                                            {penugasanPeer?.map(
                                                (employee: any) => (
                                                    <Card
                                                        key={employee.id}
                                                        className="group relative overflow-hidden border-0 bg-linear-to-br from-white to-blue-50 pb-2 shadow-lg transition-all duration-300 hover:scale-105 hover:shadow-2xl"
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

                                                            <h3 className="mb-1 overflow-hidden bg-linear-to-r from-gray-800 to-blue-600 bg-clip-text text-xl font-bold text-ellipsis whitespace-nowrap text-transparent transition-all duration-300 group-hover:from-blue-600 group-hover:to-indigo-600">
                                                                {
                                                                    employee
                                                                        .outsourcings
                                                                        .name
                                                                }
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
                                                                        ? 'bg-linear-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700'
                                                                        : 'bg-linear-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700'
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
                                                                {employee.tipe_penilai ==
                                                                'atasan'
                                                                    ? 'Atasan'
                                                                    : employee.tipe_penilai ==
                                                                            'penerima_layanan1' ||
                                                                        employee.tipe_penilai ==
                                                                            'penerima_layanan2'
                                                                      ? 'Penerima Layanan'
                                                                      : 'Teman Setingkat'}
                                                            </span>
                                                        </CardContent>

                                                        {/* Hover Effect Overlay */}
                                                        <div className="pointer-events-none absolute inset-0 bg-linear-to-t from-blue-600/10 to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>
                                                    </Card>
                                                ),
                                            )}
                                        </div>

                                        {penugasanPeer?.length === 0 && (
                                            <Card className="border-0 bg-linear-to-br from-gray-50 to-blue-50 py-16 text-center shadow-lg">
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
                            </TabsContent>
                            {/* History Tab */}
                            <TabsContent value="history" className="space-y-6">
                                {typeUser === 'outsourcing' && (
                                    <Card className="border-0 bg-linear-to-br from-blue-50 pb-7 shadow-md">
                                        <CardContent>
                                            <div className="flex items-center justify-between">
                                                <div className="flex items-center gap-3">
                                                    <div className="rounded-lg bg-gray-100 p-2">
                                                        <Users className="h-5 w-5 text-gray-600" />
                                                    </div>
                                                    <div>
                                                        <h3 className="text-lg font-bold text-gray-900">
                                                            Nilai Saya —
                                                            Semester Sebelumnya
                                                        </h3>
                                                        <p className="text-sm text-gray-500">
                                                            Riwayat penilaian
                                                            pribadi
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div className="mt-4">
                                                <Accordion
                                                    type="single"
                                                    collapsible
                                                    className="space-y-4"
                                                >
                                                    {resultScoreHistory.map(
                                                        (
                                                            resultScore: any,
                                                            index: number,
                                                        ) => {
                                                            return (
                                                                <AccordionItem
                                                                    key={
                                                                        resultScore.id ||
                                                                        index
                                                                    }
                                                                    value={
                                                                        resultScore.id
                                                                    }
                                                                    className="overflow-hidden rounded-xl border-0 bg-white"
                                                                >
                                                                    <AccordionTrigger className="px-6 py-4 hover:bg-gradient-to-r hover:from-purple-50 hover:to-indigo-50 hover:no-underline">
                                                                        <div className="flex w-full items-center justify-between pr-4">
                                                                            <div className="flex items-center gap-4 text-left">
                                                                                <div className="rounded-lg bg-gradient-to-br from-purple-100 to-indigo-100 p-3">
                                                                                    <Calendar className="h-6 w-6 text-purple-600" />
                                                                                </div>
                                                                                <div>
                                                                                    <h3 className="text-lg font-bold text-gray-900">
                                                                                        {
                                                                                            resultScore.name
                                                                                        }
                                                                                    </h3>
                                                                                    <p className="text-sm text-gray-500">
                                                                                        {
                                                                                            resultScore.period
                                                                                        }
                                                                                    </p>
                                                                                </div>
                                                                            </div>

                                                                            {/* Mini Stats */}
                                                                            <div className="text-center text-sm font-bold text-blue-600">
                                                                                Selesai
                                                                            </div>
                                                                        </div>
                                                                    </AccordionTrigger>

                                                                    <AccordionContent className="bg-gradient-to-br from-gray-100 to-purple-50/30 p-6 px-6">
                                                                        {/* Employee List */}
                                                                        <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                                                                            {resultScore?.evaluators.map(
                                                                                (
                                                                                    score: any,
                                                                                    index: number,
                                                                                ) => (
                                                                                    <Card
                                                                                        className="gap-2.5 border-l-4 border-l-purple-500 bg-gradient-to-br from-purple-50 to-white px-4 py-7 shadow-md"
                                                                                        key={
                                                                                            index
                                                                                        }
                                                                                    >
                                                                                        <div className="mb-3 flex items-start justify-between">
                                                                                            <div className="flex items-center gap-2">
                                                                                                <div className="rounded-md bg-indigo-50 p-2">
                                                                                                    {score.type ===
                                                                                                    'atasan' ? (
                                                                                                        <Briefcase className="h-5 w-5 text-indigo-600" />
                                                                                                    ) : score.type ===
                                                                                                      'teman_setingkat' ? (
                                                                                                        <Users className="h-5 w-5 text-indigo-600" />
                                                                                                    ) : score.type ===
                                                                                                      'penerima_layanan' ? (
                                                                                                        <Award className="h-5 w-5 text-indigo-600" />
                                                                                                    ) : null}
                                                                                                </div>
                                                                                                <div>
                                                                                                    <h4 className="text-sm font-semibold text-gray-900">
                                                                                                        {/* {score.type
                                                                                                            .replace(
                                                                                                                '_',
                                                                                                                ' ',
                                                                                                            )
                                                                                                            .replace(
                                                                                                                /^./,
                                                                                                                (
                                                                                                                    c: string,
                                                                                                                ) =>
                                                                                                                    c.toUpperCase(),
                                                                                                            )}{' '}
                                                                                                        (
                                                                                                        {(
                                                                                                            score?.bobot *
                                                                                                            100
                                                                                                        ).toFixed(
                                                                                                            0,
                                                                                                        )}
                                                                                                        %) */}
                                                                                                    </h4>

                                                                                                    <p className="text-xs text-gray-500">
                                                                                                        {score.type ===
                                                                                                        'atasan'
                                                                                                            ? 'Penilaian oleh atasan langsung'
                                                                                                            : ''}
                                                                                                        {score.type ===
                                                                                                        'teman_setingkat'
                                                                                                            ? 'Penilaian oleh teman setingkat'
                                                                                                            : ''}
                                                                                                        {score.type ===
                                                                                                        'penerima_layanan'
                                                                                                            ? 'Penilaian oleh penerima layanan'
                                                                                                            : ''}
                                                                                                    </p>
                                                                                                </div>
                                                                                            </div>
                                                                                            <Badge
                                                                                                className={`${
                                                                                                    score.status ===
                                                                                                    'completed'
                                                                                                        ? 'bg-green-100 text-green-800'
                                                                                                        : 'bg-gray-100 text-gray-800'
                                                                                                } shadow-sm`}
                                                                                            >
                                                                                                {score.status ===
                                                                                                'completed'
                                                                                                    ? 'Selesai'
                                                                                                    : 'Belum Menilai'}
                                                                                            </Badge>
                                                                                        </div>

                                                                                        <div className="flex flex-col items-center">
                                                                                            <div className="mb-6 w-2/3 rounded-lg bg-white p-3 text-center shadow-sm">
                                                                                                <div className="text-sm text-gray-600">
                                                                                                    Score
                                                                                                    Asli
                                                                                                </div>
                                                                                                <div
                                                                                                    className={`text-2xl font-bold ${getScoreColor(score?.averageScore)}`}
                                                                                                >
                                                                                                    {
                                                                                                        score?.averageScore
                                                                                                    }
                                                                                                </div>
                                                                                            </div>
                                                                                            <div className="text-center font-mono text-sm opacity-90">
                                                                                                {
                                                                                                    score?.averageScore
                                                                                                }{' '}
                                                                                                ×{' '}
                                                                                                {(
                                                                                                    score?.bobot *
                                                                                                    100
                                                                                                ).toFixed(
                                                                                                    0,
                                                                                                )}

                                                                                                %
                                                                                                =
                                                                                            </div>

                                                                                            <div className="text-center text-4xl font-bold text-gray-900">
                                                                                                {
                                                                                                    score.weightedScore
                                                                                                }
                                                                                            </div>
                                                                                        </div>

                                                                                        <div className="text-center text-sm text-gray-600">
                                                                                            {score.notes
                                                                                                ? score.notes
                                                                                                : score.status ===
                                                                                                    'completed'
                                                                                                  ? '"Tidak ada saran dari penilai"'
                                                                                                  : '"Belum ada catatan"'}
                                                                                        </div>
                                                                                    </Card>
                                                                                ),
                                                                            )}
                                                                        </div>
                                                                    </AccordionContent>
                                                                </AccordionItem>
                                                            );
                                                        },
                                                    )}
                                                </Accordion>
                                            </div>
                                        </CardContent>
                                    </Card>
                                )}

                                <Card className="border-0 bg-linear-to-br from-blue-50 shadow-md">
                                    <CardContent>
                                        <div className="mb-7 flex items-center justify-between">
                                            <div className="flex items-center gap-3">
                                                <div className="rounded-lg bg-gray-100 p-2">
                                                    <Users className="h-5 w-5 text-gray-600" />
                                                </div>
                                                <div>
                                                    <h3 className="text-lg font-bold text-gray-900">
                                                        Riwayat Penilaian
                                                        Semester
                                                    </h3>
                                                    <p className="text-sm text-gray-500">
                                                        Lihat hasil penilaian
                                                        dari semester sebelumnya
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        {/* Semester History Accordion */}
                                        <Accordion
                                            type="single"
                                            collapsible
                                            className="space-y-4"
                                        >
                                            {semesterHistory.map(
                                                (semester: any) => {
                                                    const semesterStats = {
                                                        total: semester
                                                            .employees.length,
                                                        completed:
                                                            semester.employees.filter(
                                                                (emp: any) =>
                                                                    emp.status ===
                                                                    'completed',
                                                            ).length,
                                                        avgScore: Math.round(
                                                            semester.employees.reduce(
                                                                (
                                                                    acc: number,
                                                                    emp: any,
                                                                ) =>
                                                                    acc +
                                                                    (emp.score ||
                                                                        0),
                                                                0,
                                                            ) /
                                                                semester
                                                                    .employees
                                                                    .length,
                                                        ),
                                                    };

                                                    return (
                                                        <AccordionItem
                                                            key={semester.id}
                                                            value={semester.id}
                                                            className="overflow-hidden rounded-xl border-0 bg-white"
                                                        >
                                                            <AccordionTrigger className="px-6 py-4 hover:bg-gradient-to-r hover:from-purple-50 hover:to-indigo-50 hover:no-underline">
                                                                <div className="flex w-full items-center justify-between pr-4">
                                                                    <div className="flex items-center gap-4 text-left">
                                                                        <div className="rounded-lg bg-gradient-to-br from-purple-100 to-indigo-100 p-3">
                                                                            <Calendar className="h-6 w-6 text-purple-600" />
                                                                        </div>
                                                                        <div>
                                                                            <h3 className="text-lg font-bold text-gray-900">
                                                                                {
                                                                                    semester.name
                                                                                }
                                                                            </h3>
                                                                            <p className="text-sm text-gray-500">
                                                                                {
                                                                                    semester.period
                                                                                }
                                                                            </p>
                                                                        </div>
                                                                    </div>

                                                                    {/* Mini Stats */}
                                                                    <div className="text-center">
                                                                        <div className="text-lg font-bold text-blue-600">
                                                                            {
                                                                                semesterStats.total
                                                                            }
                                                                        </div>
                                                                        <div className="text-xs text-gray-500">
                                                                            Pegawai
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </AccordionTrigger>

                                                            <AccordionContent className="bg-gradient-to-br from-gray-100 to-purple-50/30 p-6 px-6">
                                                                {/* Employee List */}
                                                                <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                                                                    {semester.employees.map(
                                                                        (
                                                                            employee: any,
                                                                        ) => (
                                                                            <Card
                                                                                key={
                                                                                    employee.id
                                                                                }
                                                                                className="group overflow-hidden border-0 bg-white shadow-md transition-all duration-300 hover:scale-105 hover:shadow-xl"
                                                                            >
                                                                                <CardContent className="p-4">
                                                                                    <div className="flex items-start gap-3">
                                                                                        <img
                                                                                            src={`/${employee.outsourcings.image}`}
                                                                                            alt={
                                                                                                employee
                                                                                                    .outsourcings
                                                                                                    .name
                                                                                            }
                                                                                            className="h-14 w-14 rounded-full border-2 border-purple-200 object-cover shadow-sm"
                                                                                        />
                                                                                        <div className="min-w-0 flex-1">
                                                                                            <h4 className="truncate font-semibold text-gray-900">
                                                                                                {
                                                                                                    employee
                                                                                                        .outsourcings
                                                                                                        .name
                                                                                                }
                                                                                            </h4>
                                                                                            <p className="text-xs text-gray-500">
                                                                                                {
                                                                                                    employee
                                                                                                        .outsourcings
                                                                                                        .jabatan
                                                                                                }
                                                                                            </p>
                                                                                            <div className="mt-2 flex items-center justify-between">
                                                                                                <Badge
                                                                                                    className={`text-xs ${
                                                                                                        employee.status ===
                                                                                                        'completed'
                                                                                                            ? 'bg-green-100 text-green-700 hover:bg-green-200'
                                                                                                            : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                                                                                                    }`}
                                                                                                >
                                                                                                    {employee.status ===
                                                                                                    'completed' ? (
                                                                                                        <CheckCircle className="mr-1 h-3 w-3" />
                                                                                                    ) : (
                                                                                                        <Clock className="mr-1 h-3 w-3" />
                                                                                                    )}
                                                                                                    {employee.status ===
                                                                                                    'completed'
                                                                                                        ? 'Selesai'
                                                                                                        : 'Pending'}
                                                                                                </Badge>
                                                                                                {employee.score && (
                                                                                                    <div className="flex items-center gap-1 text-sm font-bold text-purple-600">
                                                                                                        <Award className="h-4 w-4" />
                                                                                                        {
                                                                                                            employee.score
                                                                                                        }
                                                                                                    </div>
                                                                                                )}
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>

                                                                                    <Link
                                                                                        href={`/penilaian/${employee.uuid}`}
                                                                                        className="mt-3 flex items-center justify-center gap-2 rounded-lg bg-gradient-to-r from-purple-500 to-indigo-600 py-2 text-sm font-medium text-white transition-all hover:from-purple-600 hover:to-indigo-700"
                                                                                    >
                                                                                        <ClipboardList className="h-4 w-4" />
                                                                                        Lihat
                                                                                        Detail
                                                                                    </Link>
                                                                                </CardContent>
                                                                            </Card>
                                                                        ),
                                                                    )}
                                                                </div>
                                                            </AccordionContent>
                                                        </AccordionItem>
                                                    );
                                                },
                                            )}
                                        </Accordion>

                                        {/* Empty State for History */}
                                        {semesterHistory.length === 0 && (
                                            <Card className="border-0 bg-gradient-to-br from-gray-50 to-blue-50 py-16 text-center shadow-lg">
                                                <CardContent>
                                                    <div className="mx-auto mb-4 w-fit rounded-full bg-white p-6 shadow-lg">
                                                        <History className="h-16 w-16 text-gray-400" />
                                                    </div>
                                                    <h3 className="mb-2 text-xl font-bold text-gray-900">
                                                        Belum Ada Riwayat
                                                        Semester
                                                    </h3>
                                                    <p className="mx-auto max-w-md text-gray-500">
                                                        Riwayat penilaian dari
                                                        semester sebelumnya akan
                                                        muncul di sini
                                                    </p>
                                                </CardContent>
                                            </Card>
                                        )}
                                    </CardContent>
                                </Card>
                            </TabsContent>
                        </Tabs>
                    </div>
                </main>
            </div>
        </>
    );
}
