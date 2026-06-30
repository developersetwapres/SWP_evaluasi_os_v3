'use client';

import { ViewScoreComponent } from '@/components/penilaian/view-score-component';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { home } from '@/routes';
import { Outsourcing } from '@/types';
import { Head, Link } from '@inertiajs/react';
import { ArrowLeft, ClipboardCheck, FileText, UserCheck } from 'lucide-react';

interface ViewScoreProps {
    outsourcing: Outsourcing;
    evaluator: any;
    evaluationData: any;
    overallNotes: string;
    rekapPerAspek: any;
    tipePenilai: string;
}

export default function ViewScore({
    outsourcing,
    evaluator,
    evaluationData,
    overallNotes,
    rekapPerAspek,
    tipePenilai,
}: ViewScoreProps) {
    return (
        <>
            <Head title="View Score" />
            <div className="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
                <header className="sticky top-0 z-10 border-b bg-white shadow-sm">
                    <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                        <div className="flex items-center justify-between py-4">
                            <Link href={home.url()}>
                                <Button
                                    variant="ghost"
                                    className="flex items-center space-x-2"
                                >
                                    <ArrowLeft className="h-4 w-4" />
                                    <span>Kembali</span>
                                </Button>
                            </Link>
                        </div>
                    </div>
                </header>

                <main className="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
                    <div className="space-y-8">
                        <div className="grid gap-8 md:grid-cols-2">
                            {/* Evaluator Card */}
                            <Card className="relative gap-1 overflow-hidden border-0 bg-gradient-to-br from-green-500 to-emerald-600 pb-0 text-white shadow-2xl">
                                <div className="absolute top-0 right-0 h-32 w-32 translate-x-16 -translate-y-16 rounded-full bg-white/10"></div>
                                <div className="absolute bottom-0 left-0 h-24 w-24 -translate-x-12 translate-y-12 rounded-full bg-white/10"></div>

                                <CardHeader className="relative">
                                    <div className="mb-4 flex items-center space-x-3">
                                        <div className="rounded-full bg-white/20 p-2">
                                            <UserCheck className="h-6 w-6 text-white" />
                                        </div>
                                        <div>
                                            <Badge className="border-white/30 bg-white/20 font-semibold text-white">
                                                PENILAI
                                            </Badge>
                                            <CardTitle className="mt-1 text-sm text-green-100">
                                                Yang Memberikan Penilaian
                                            </CardTitle>
                                        </div>
                                    </div>
                                </CardHeader>

                                <CardContent className="relative pb-8 text-center">
                                    <div className="relative mx-2 mb-6">
                                        <div className="absolute inset-0 scale-110 animate-pulse rounded-full bg-white/20"></div>
                                        <img
                                            src={`/storage/${evaluator.image}`}
                                            alt={evaluator.name}
                                            className="mx-auto h-20 w-20 rounded-full border-4 border-white shadow-lg"
                                        />
                                    </div>

                                    <h3 className="mb-1 text-2xl font-bold text-white">
                                        {evaluator.name}
                                    </h3>
                                    <p className="text-lg font-medium text-green-100">
                                        {evaluator?.jabatan_id
                                            ? evaluator?.jabatan?.nama_jabatan
                                            : evaluator?.jabatan}
                                    </p>

                                    <div className="mt-3">
                                        <Badge className="border-white/30 bg-white/20 px-4 py-2 text-sm font-semibold text-white">
                                            {tipePenilai == 'atasan'
                                                ? 'Atasan'
                                                : tipePenilai ==
                                                    'penerima_layanan'
                                                  ? 'Penerima Layanan'
                                                  : 'Teman Setingkat'}
                                        </Badge>
                                    </div>
                                </CardContent>
                            </Card>

                            {/* outsourcing Card */}
                            <Card className="relative gap-1 overflow-hidden border-0 bg-gradient-to-br from-blue-500 to-indigo-600 pb-0 text-white shadow-2xl">
                                <div className="absolute top-0 right-0 h-32 w-32 translate-x-16 -translate-y-16 rounded-full bg-white/10"></div>
                                <div className="absolute bottom-0 left-0 h-24 w-24 -translate-x-12 translate-y-12 rounded-full bg-white/10"></div>

                                <CardHeader className="relative">
                                    <div className="mb-4 flex items-center space-x-3">
                                        <div className="rounded-full bg-white/20 p-2">
                                            <ClipboardCheck className="h-6 w-6 text-white" />
                                        </div>
                                        <div>
                                            <Badge className="border-white/30 bg-white/20 font-semibold text-white">
                                                YANG DINILAI
                                            </Badge>
                                            <CardTitle className="mt-1 text-sm text-blue-100">
                                                Pegawai yang Sedang Dievaluasi
                                            </CardTitle>
                                        </div>
                                    </div>
                                </CardHeader>

                                <CardContent className="relative pb-8 text-center">
                                    <div className="relative mx-2 mb-6">
                                        <div className="absolute inset-0 scale-110 animate-pulse rounded-full bg-white/20"></div>
                                        <img
                                            src={`/storage/${outsourcing.image}`}
                                            alt={outsourcing.name}
                                            className="mx-auto h-20 w-20 rounded-full border-4 border-white shadow-lg"
                                        />
                                    </div>

                                    <h3 className="mb-1 text-2xl font-bold text-white">
                                        {outsourcing.name}
                                    </h3>
                                    <p className="text-lg font-medium text-blue-100">
                                        {outsourcing.jabatan.nama_jabatan}
                                    </p>

                                    <div className="mt-3">
                                        <Badge className="border-white/30 bg-white/20 px-4 py-2 text-sm font-semibold text-white">
                                            {outsourcing?.biro?.nama_biro}
                                        </Badge>
                                    </div>
                                </CardContent>
                            </Card>
                        </div>

                        <div className="space-y-5">
                            {/* Overall Summary Card - UPDATED to match evaluation-form.tsx concept */}
                            <ViewScoreComponent
                                rekapPerAspek={rekapPerAspek}
                                evaluationData={evaluationData}
                            />

                            {/* Overall Notes */}
                            {overallNotes && (
                                <Card className="gap-0 border-l-4 border-l-yellow-500 py-4">
                                    <CardHeader className="bg-yellow-50 py-2">
                                        <CardTitle className="flex items-center space-x-2 text-xl text-yellow-800">
                                            <FileText className="h-6 w-6" />
                                            <span>Catatan Keseluruhan</span>
                                        </CardTitle>
                                    </CardHeader>
                                    <CardContent className="p-6">
                                        <div className="rounded-lg border border-yellow-200 bg-white p-4">
                                            <p className="leading-relaxed text-gray-800">
                                                {overallNotes}
                                            </p>
                                        </div>
                                    </CardContent>
                                </Card>
                            )}
                        </div>
                    </div>
                </main>
            </div>
        </>
    );
}
