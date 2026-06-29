'use client';

import { Badge } from '@/components/ui/badge';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { CheckCircle, Info } from 'lucide-react';

const getScoreClassification = (score: number) => {
    if (score <= 50)
        return {
            label: 'SK (Sangat Kurang)',
            color: 'bg-red-100 text-red-800 border-red-200',
            range: '≤50',
        };
    if (score <= 75)
        return {
            label: 'K (Kurang)',
            color: 'bg-orange-100 text-orange-800 border-orange-200',
            range: '51-75',
        };
    if (score <= 90)
        return {
            label: 'B (Baik)',
            color: 'bg-blue-100 text-blue-800 border-blue-200',
            range: '76-90',
        };
    return {
        label: 'SB (Sangat Baik)',
        color: 'bg-green-100 text-green-800 border-green-200',
        range: '91-100',
    };
};

export function ViewScoreComponent({ rekapPerAspek, evaluationData }: any) {
    function getAspectStats(aspectTitle: string) {
        const aspect = evaluationData.find((a: any) => a.title === aspectTitle);

        if (!aspect) return { total: 0, count: 0, avg: 0 };

        const scoresList = aspect.kriteria.map(
            (c: any) => c.penilaian?.nilai || 0,
        );

        const total = scoresList.reduce((a: number, b: number) => a + b, 0);

        const count = aspect.kriteria.length;
        const avg = count ? parseFloat((total / count).toFixed(2)) : 0;

        return { total, count, avg };
    }

    const getScoreColor = (score: number) => {
        if (score >= 91) return 'text-green-600 bg-green-50';
        if (score >= 81) return 'text-blue-600 bg-blue-50';
        if (score >= 71) return 'text-yellow-600 bg-yellow-50';
        if (score >= 61) return 'text-orange-600 bg-orange-50';
        return 'text-red-600 bg-red-50';
    };

    return (
        <>
            <Card className="gap-0 bg-gradient-to-r from-blue-500 to-indigo-600 text-white">
                <CardHeader>
                    <CardTitle className="flex items-center space-x-3 text-2xl">
                        <div className="rounded-full bg-white/20 p-3">
                            <CheckCircle className="h-8 w-8" />
                        </div>
                        <span>Review Penilaian Keseluruhan</span>
                    </CardTitle>
                </CardHeader>
                <CardContent>
                    <div className="grid gap-6 md:grid-cols-3">
                        {rekapPerAspek?.aspects?.map(
                            (aspek: any, index: number) => {
                                const nilaiAkhir = aspek.nilai * aspek.bobot;

                                return (
                                    <div key={index} className="text-center">
                                        <div className="text-sm text-blue-100">
                                            {aspek.title}
                                        </div>

                                        <div className="text-xs text-blue-100">
                                            {aspek.nilai.toFixed(2)} ×{' '}
                                            {aspek.bobot * 100}%
                                        </div>

                                        <div className="mt-1 text-3xl font-extrabold tracking-tight">
                                            {nilaiAkhir.toFixed(2)}
                                        </div>
                                    </div>
                                );
                            },
                        )}

                        {/* SKOR AKHIR */}
                        <div className="text-center">
                            <div className="text-sm text-blue-100">
                                Skor Akhir
                            </div>

                            <div className="text-xs text-blue-100">
                                {rekapPerAspek?.aspects
                                    ?.map((a: any) =>
                                        (a.nilai * a.bobot).toFixed(2),
                                    )
                                    .join(' + ')}
                            </div>

                            <div className="mt-1 text-3xl font-extrabold tracking-tight">
                                {rekapPerAspek?.finalTotalScore?.toFixed(2)}
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>

            {/* Detailed Review by Aspect - UPDATED to show Total, Count, and emphasize Avg */}
            {evaluationData.map((aspect: any, aspectIndex: number) => {
                console.log(aspect);

                return (
                    <Card
                        key={aspectIndex}
                        className="gap-4 border-l-4 border-l-blue-500"
                    >
                        <CardHeader className="bg-gradient-to-r from-gray-50 to-blue-50">
                            <div className="flex items-center justify-between">
                                <div className="flex items-center space-x-4">
                                    <div className="flex h-12 w-12 items-center justify-center rounded-full bg-blue-600 text-xl font-bold text-white">
                                        {aspectIndex + 1}
                                    </div>
                                    <div>
                                        <CardTitle className="text-2xl text-blue-800">
                                            {aspect.title}
                                        </CardTitle>
                                        <CardDescription className="text-blue-600">
                                            {aspect.kriteria.length} Kriteria
                                        </CardDescription>
                                    </div>
                                </div>

                                {(() => {
                                    const { total, avg } = getAspectStats(
                                        aspect.title,
                                    );
                                    return (
                                        <div
                                            className={`rounded-xl px-6 py-3 ${getScoreColor(avg)}`}
                                        >
                                            <div className="text-sm">
                                                Total Skor: {total}
                                            </div>
                                            <div className="text-sm font-semibold">
                                                Rata-Rata : {avg}
                                            </div>
                                        </div>
                                    );
                                })()}
                            </div>
                        </CardHeader>

                        <CardContent className="p-6 pt-3">
                            <div className="space-y-6">
                                {aspect.kriteria.map(
                                    (
                                        criterion: any,
                                        criterionIndex: number,
                                    ) => {
                                        const score =
                                            criterion.penilaian?.nilai || 0;

                                        const classification =
                                            getScoreClassification(score);

                                        return (
                                            <div
                                                key={criterionIndex}
                                                className="rounded-lg border-l-4 border-l-gray-300 bg-gray-50 p-5"
                                            >
                                                <div className="mb-4 flex items-start justify-between">
                                                    <div className="flex flex-1 items-start space-x-3">
                                                        <div className="flex h-8 w-8 items-center justify-center rounded-full bg-gray-600 text-sm font-bold text-white">
                                                            {aspectIndex + 1}.
                                                            {criterionIndex + 1}
                                                        </div>
                                                        <div className="flex-1">
                                                            <h4 className="text-xl font-semibold text-gray-800">
                                                                {
                                                                    criterion.title
                                                                }
                                                            </h4>
                                                            <div className="mt-2 text-lg font-bold text-gray-900">
                                                                Nilai: {score}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div className="ml-4">
                                                        <Badge
                                                            className={`${classification.color} border px-3 py-1 text-sm font-semibold`}
                                                        >
                                                            {
                                                                classification.label
                                                            }
                                                        </Badge>
                                                    </div>
                                                </div>

                                                <div className="mt-4 rounded-lg border border-gray-200 bg-white p-4">
                                                    <h5 className="mb-2 flex items-center space-x-2 font-medium text-gray-700">
                                                        <Info className="h-4 w-4" />
                                                        <span>
                                                            Indikator Penilaian:
                                                        </span>
                                                    </h5>
                                                    <ul className="space-y-1 text-sm text-gray-600">
                                                        {criterion?.indikators?.map(
                                                            (
                                                                indicator: any,
                                                                idx: number,
                                                            ) => (
                                                                <li
                                                                    key={idx}
                                                                    className="flex items-start space-x-2"
                                                                >
                                                                    <span className="mt-1 text-blue-500">
                                                                        •
                                                                    </span>
                                                                    <span>
                                                                        {
                                                                            indicator?.deskripsi
                                                                        }
                                                                    </span>
                                                                </li>
                                                            ),
                                                        )}
                                                    </ul>
                                                </div>
                                            </div>
                                        );
                                    },
                                )}
                            </div>
                        </CardContent>
                    </Card>
                );
            })}
        </>
    );
}
