'use client';

import { EmployeeHeader } from '@/components/penilaian/detail/employee-header';
import { EmployeeNavigation } from '@/components/penilaian/detail/employee-navigation';
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
import { reset } from '@/routes/penugasan';
import {
    getScoreBadgeColor,
    getScoreColor,
    getScoreLabel,
} from '@/utils/score';
import { router } from '@inertiajs/react';
import { BarChart3, Calculator, Users } from 'lucide-react';
import { useState } from 'react';

export default function nilaiAkhir({ rekapPilarEvaluator }: any) {
    const [isResetNilaiOpen, setIsResetNilaiOpen] = useState(false);
    const [selectedPenilaian, setSelectedPenilaian] = useState<any>(null);

    const handleResetNilai = () => {
        setIsResetNilaiOpen(false);
        setSelectedPenilaian(null);

        router.post(
            reset.url(selectedPenilaian.uuidPenugasan),
            {},
            {
                onSuccess: () => {
                    // toast({
                    //     title: 'Berhasil',
                    //     description: 'Nilai sudah direset',
                    // });
                },
            },
        );
    };
    return (
        <div className="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
            <main className="mx-auto max-w-7xl px-4 pb-8 sm:px-6 lg:px-8">
                <div className="space-y-8">
                    <EmployeeNavigation
                        employeeUuid={rekapPilarEvaluator.uuid}
                    />

                    {/* Employee Profile Card */}
                    <Card className="bg-gradient-to-r from-purple-400 to-purple-600 text-white">
                        <CardHeader>
                            <div className="flex items-center justify-between">
                                <div className="flex items-center space-x-6">
                                    <img
                                        src={`/storage/${rekapPilarEvaluator?.image}`}
                                        alt={rekapPilarEvaluator?.name}
                                        className="h-24 w-24 rounded-full border-4 border-white shadow-lg"
                                    />
                                    <div>
                                        <CardTitle className="mb-2 text-3xl">
                                            {rekapPilarEvaluator?.name}
                                        </CardTitle>
                                        <CardDescription className="text-lg text-indigo-100">
                                            {rekapPilarEvaluator?.jabatan} •{' '}
                                            {rekapPilarEvaluator?.unit_kerja}
                                        </CardDescription>
                                        <div className="mt-4 flex items-center space-x-4">
                                            <Badge
                                                className={
                                                    rekapPilarEvaluator?.status ===
                                                    'completed'
                                                        ? 'border-green-400 bg-green-500 text-white'
                                                        : 'border-yellow-400 bg-yellow-500 text-white'
                                                }
                                            >
                                                {rekapPilarEvaluator?.status ===
                                                'completed'
                                                    ? 'Evaluasi Selesai'
                                                    : 'Dalam Progress'}
                                            </Badge>
                                        </div>
                                    </div>
                                </div>
                                <div className="text-right">
                                    <div className="mb-2 text-6xl font-bold">
                                        {rekapPilarEvaluator?.finalTotalScore?.toFixed(
                                            2,
                                        )}
                                    </div>
                                    <Badge
                                        className={`${getScoreBadgeColor(rekapPilarEvaluator?.finalTotalScore)} border-2 px-4 py-2 text-lg`}
                                    >
                                        {getScoreLabel(
                                            rekapPilarEvaluator?.finalTotalScore,
                                        )}
                                    </Badge>
                                    <p className="mt-2 text-indigo-100">
                                        Nilai Akhir Penilaian
                                    </p>
                                </div>
                            </div>
                        </CardHeader>
                    </Card>

                    {/* Rekap Pilar Berbobot */}
                    <Card className="gap-0 shadow-xl">
                        <CardHeader>
                            <CardTitle className="flex items-center space-x-3">
                                <BarChart3 className="h-6 w-6" />
                                <span>Rekap Nilai per Pilar</span>
                            </CardTitle>
                            <CardDescription>
                                Ringkasan nilai berbobot untuk setiap pilar
                                penilaian dari semua evaluator
                            </CardDescription>
                        </CardHeader>
                        <CardContent className="p-8">
                            <div className="grid grid-cols-1 gap-8 md:grid-cols-2">
                                {rekapPilarEvaluator.pilarScores.map(
                                    (pilar: any, index: any) => (
                                        <div
                                            key={index}
                                            className="transform rounded-xl border-2 border-blue-300 bg-gradient-to-br from-blue-400 to-blue-700 p-6 px-8 text-white shadow-lg transition-all duration-300 hover:scale-105 hover:shadow-2xl"
                                        >
                                            <div className="text-center">
                                                <div className="mb-4 inline-flex h-16 w-16 items-center justify-center rounded-full bg-white/20 backdrop-blur-sm">
                                                    <Calculator className="h-8 w-8" />
                                                </div>
                                                <h3 className="mb-2 text-xl font-bold">
                                                    {pilar.title}
                                                </h3>
                                                <div className="mb-4 inline-block rounded-full bg-white/20 px-3 py-1 text-sm font-medium backdrop-blur-sm">
                                                    Nilai Akhir
                                                </div>
                                                <div className="mb-4 rounded-4xl bg-white/10 p-4 backdrop-blur-sm">
                                                    <div className="mb-1 font-mono text-sm opacity-90">
                                                        {pilar?.averageScore} ×{' '}
                                                        {(
                                                            pilar?.bobot * 100
                                                        ).toFixed(0)}
                                                        % =
                                                    </div>
                                                    <div className="text-4xl font-bold">
                                                        {pilar?.weightedScore.toFixed(
                                                            2,
                                                        )}
                                                    </div>
                                                    <div className="mt-1 text-xs opacity-75">
                                                        Dari semua evaluator
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    ),
                                )}
                            </div>
                        </CardContent>
                    </Card>

                    {/* Rekap Nilai Akhir per Evaluator */}
                    <Card className="gap-0 shadow-xl">
                        <CardHeader>
                            <CardTitle className="flex items-center space-x-3">
                                <Users className="h-5 w-5" />
                                <span>Rekap Nilai Akhir per Evaluator</span>
                            </CardTitle>
                            <CardDescription>
                                Hasil akhir penilaian dari setiap evaluator
                                setelah dikalikan dengan bobot masing-masing
                            </CardDescription>
                        </CardHeader>
                        <CardContent className="p-8">
                            <div className="grid grid-cols-1 gap-6 md:grid-cols-3">
                                {rekapPilarEvaluator?.evaluatorScores.map(
                                    (evaluator: any, index: any) => {
                                        const evaluatorType =
                                            evaluator.type === 'atasan'
                                                ? 'Atasan'
                                                : evaluator.type ===
                                                    'penerima_layanan'
                                                  ? 'Penerima Layanan'
                                                  : 'Rekan Kerja';

                                        const weightedScore =
                                            evaluator.weightedScore;

                                        return (
                                            <div
                                                key={index}
                                                className="transform rounded-lg border-2 border-blue-200 bg-gradient-to-r from-blue-50 to-blue-100 p-6 shadow-lg transition-all duration-300 hover:scale-105 hover:shadow-xl"
                                            >
                                                <div className="mb-4 text-center">
                                                    <h4 className="mb-2 text-lg font-bold text-blue-800">
                                                        {
                                                            evaluator.evaluatorName
                                                        }
                                                    </h4>

                                                    <div
                                                        className="mb-4 inline-block rounded-full bg-blue-100 px-3 py-1 text-sm font-medium text-blue-700 backdrop-blur-sm"
                                                        onDoubleClick={() => {
                                                            (setIsResetNilaiOpen(
                                                                true,
                                                            ),
                                                                setSelectedPenilaian(
                                                                    evaluator,
                                                                ));
                                                        }}
                                                    >
                                                        <button>
                                                            {evaluatorType}
                                                        </button>
                                                    </div>
                                                </div>

                                                <div className="mb-4 rounded-lg bg-white p-3 text-center">
                                                    <div className="text-sm text-gray-600">
                                                        Score Asli
                                                    </div>
                                                    <div
                                                        className={`text-2xl font-bold ${getScoreColor(evaluator?.averageScore)}`}
                                                    >
                                                        {
                                                            evaluator?.averageScore
                                                        }
                                                    </div>
                                                </div>

                                                <div className="rounded-lg bg-white p-4 text-center">
                                                    <div className="mb-2 font-mono text-xs text-blue-600">
                                                        {
                                                            evaluator?.averageScore
                                                        }{' '}
                                                        ×{' '}
                                                        {(
                                                            evaluator.bobot *
                                                            100
                                                        ).toFixed(0)}
                                                        % =
                                                    </div>
                                                    <div className="text-3xl font-bold text-red-500">
                                                        {weightedScore.toFixed(
                                                            2,
                                                        )}
                                                    </div>
                                                    <div className="mt-1 text-xs text-blue-500">
                                                        Kontribusi akhir
                                                    </div>
                                                </div>
                                            </div>
                                        );
                                    },
                                )}
                            </div>
                        </CardContent>
                    </Card>
                </div>

                {/* ResetNilai Confirmation Dialog */}
                <Dialog
                    open={isResetNilaiOpen}
                    onOpenChange={setIsResetNilaiOpen}
                >
                    <DialogContent className="sm:max-w-md">
                        <DialogHeader>
                            <DialogTitle>Reset Nilai</DialogTitle>
                            <DialogDescription>
                                Apakah Anda yakin ingin mereset penilaian{' '}
                                {selectedPenilaian?.evaluatorName}?
                            </DialogDescription>
                        </DialogHeader>

                        <DialogFooter>
                            <Button
                                variant="outline"
                                onClick={() => {
                                    (setIsResetNilaiOpen(false),
                                        setSelectedPenilaian(null));
                                }}
                            >
                                Batal
                            </Button>
                            <Button
                                variant="destructive"
                                onClick={handleResetNilai}
                            >
                                Reset Nilai
                            </Button>
                        </DialogFooter>
                    </DialogContent>
                </Dialog>
            </main>
        </div>
    );
}
