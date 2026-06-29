'use client';

import { EmployeeHeader } from '@/components/penilaian/detail/employee-header';
import { EmployeeNavigation } from '@/components/penilaian/detail/employee-navigation';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Calculator } from 'lucide-react';

const getScoreLabel = (score: number) => {
    if (score >= 91) return 'Sangat Baik';
    if (score >= 81) return 'Baik';
    if (score >= 71) return 'Butuh Perbaikan';
    if (score >= 61) return 'Kurang';
    return 'Sangat Kurang';
};

const getScoreBadgeColor = (score: number) => {
    if (score >= 91) return 'bg-green-100 text-green-800 border-green-200';
    if (score >= 81) return 'bg-blue-100 text-blue-800 border-blue-200';
    if (score >= 71) return 'bg-yellow-100 text-yellow-800 border-yellow-200';
    if (score >= 61) return 'bg-orange-100 text-orange-800 border-orange-200';
    return 'bg-red-100 text-red-800 border-red-200';
};

const getScoreColor = (score: number) => {
    if (score >= 91) return 'text-green-600';
    if (score >= 81) return 'text-blue-600';
    if (score >= 71) return 'text-yellow-600';
    if (score >= 61) return 'text-orange-600';
    return 'text-red-600';
};

export default function rekapNilai({ peraspek }: any) {
    if (!peraspek || !peraspek.aspects) {
        return null;
    }

    return (
        <div className="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
            <EmployeeHeader />

            <main className="mx-auto max-w-7xl px-4 pb-8 sm:px-6 lg:px-8">
                <div className="space-y-8">
                    {peraspek?.uuid && (
                        <EmployeeNavigation employeeUuid={peraspek.uuid} />
                    )}

                    {/* Weighted Scoring Info */}
                    <Card className="border-blue-200 bg-blue-50 pt-0 pb-6">
                        <CardContent className="pt-6">
                            <div className="mb-4 flex items-center space-x-2">
                                <Calculator className="h-6 w-6 text-blue-600" />
                                <span className="text-lg font-bold text-blue-800">
                                    Bobot Evaluator
                                </span>
                            </div>

                            <div className="grid grid-cols-3 gap-6">
                                {peraspek.aspects.length > 0 &&
                                    peraspek.aspects[0].evaluators.map(
                                        (e: any, i: number) => (
                                            <div
                                                key={i}
                                                className="rounded-lg border border-blue-300 bg-blue-100 p-4 text-center"
                                            >
                                                <div className="text-2xl font-bold text-blue-800">
                                                    {(e.bobot * 100).toFixed(0)}
                                                    %
                                                </div>
                                                <div className="font-medium text-blue-700">
                                                    {e.evaluatorType}
                                                </div>
                                            </div>
                                        ),
                                    )}
                            </div>
                        </CardContent>
                    </Card>

                    {/* Perhitungan Berbobot per Aspek */}
                    <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
                        {peraspek.aspects.map((aspek: any, index: number) => {
                            const weightedList = aspek.evaluators.map(
                                (e: any) => Number(e.weightedScore).toFixed(2),
                            );

                            return (
                                <Card key={index} className="gap-3">
                                    <CardHeader>
                                        <CardTitle className="text-xl text-green-800">
                                            {aspek.aspectTitle}
                                        </CardTitle>
                                    </CardHeader>
                                    <CardContent>
                                        {aspek.evaluators.map(
                                            (penilai: any, index: number) => (
                                                <div
                                                    key={index}
                                                    className="mb-4 rounded-lg border-2 border-green-200 bg-gradient-to-r from-green-50 to-green-100 p-6"
                                                >
                                                    <div className="mb-4 flex items-center justify-between">
                                                        <div>
                                                            <h4 className="text-lg font-bold text-green-800">
                                                                {
                                                                    penilai?.evaluatorName
                                                                }
                                                            </h4>
                                                            <p className="text-sm text-green-600">
                                                                {penilai?.evaluatorType ==
                                                                'atasan'
                                                                    ? 'Atasan'
                                                                    : penilai?.evaluatorType ==
                                                                        'penerima_layanan'
                                                                      ? 'Penerima Layanan'
                                                                      : 'Teman Setingkat'}
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <div className="mb-4 flex items-center justify-between rounded-sm bg-white p-3 text-sm">
                                                        <span className="text-gray-600">
                                                            Score Inputan:
                                                        </span>
                                                        <div className="flex items-center space-x-2">
                                                            <span
                                                                className={`text-lg font-bold ${getScoreColor(penilai?.averageScore)}`}
                                                            >
                                                                {
                                                                    penilai?.averageScore
                                                                }
                                                            </span>
                                                            <Badge
                                                                className={`${getScoreBadgeColor(penilai?.averageScore)} text-xs`}
                                                            >
                                                                {getScoreLabel(
                                                                    penilai?.averageScore,
                                                                )}
                                                            </Badge>
                                                        </div>
                                                    </div>

                                                    <div className="rounded-4xl bg-white p-4 text-center">
                                                        <div className="mb-2 text-center font-mono text-xs text-green-600">
                                                            {
                                                                penilai?.averageScore
                                                            }
                                                            {' × '}
                                                            {penilai.bobot *
                                                                100}
                                                            {'% = '}
                                                        </div>
                                                        <div className="text-4xl font-bold text-red-500">
                                                            {
                                                                penilai?.weightedScore
                                                            }
                                                        </div>
                                                        <div className="mt-1 text-xs text-green-500">
                                                            Kontribusi ke nilai
                                                            akhir
                                                        </div>
                                                    </div>
                                                </div>
                                            ),
                                        )}

                                        {/* Total */}
                                        <div className="rounded-lg border border-blue-400 bg-gradient-to-r from-blue-100 to-blue-200 p-6">
                                            <div className="text-center">
                                                <h4 className="mb-2 text-xl font-bold text-blue-800">
                                                    {aspek.aspectTitle}
                                                </h4>
                                                <div className="mt-2 mb-3 text-xs text-blue-600">
                                                    <div className="mt-2 mb-3 text-xs text-blue-600">
                                                        {weightedList.join(
                                                            ' + ',
                                                        )}{' '}
                                                        =
                                                    </div>
                                                </div>
                                                <div className="text-5xl font-bold text-blue-900">
                                                    <div className="text-5xl font-bold text-blue-900">
                                                        {aspek.total.toFixed(2)}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </CardContent>
                                </Card>
                            );
                        })}
                    </div>
                </div>
            </main>
        </div>
    );
}
