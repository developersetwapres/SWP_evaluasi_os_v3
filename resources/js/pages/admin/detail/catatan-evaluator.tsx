'use client';

import { EmployeeHeader } from '@/components/penilaian/detail/employee-header';
import { EmployeeNavigation } from '@/components/penilaian/detail/employee-navigation';
import { Badge } from '@/components/ui/badge';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { FileText } from 'lucide-react';

export default function catatanEvaluator({ penugasans, uuidOs }) {
    return (
        <div className="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
            <EmployeeHeader />

            <main className="mx-auto max-w-7xl px-4 pb-8 sm:px-6 lg:px-8">
                <div className="space-y-8">
                    <EmployeeNavigation employeeUuid={uuidOs} />

                    <Card className="gap-0 py-0 shadow-xl">
                        <CardHeader className="bg-gradient-to-r from-gray-50 to-blue-50 py-5 pl-7">
                            <CardTitle className="flex items-center space-x-3 text-gray-800">
                                <FileText className="h-7 w-7 text-blue-600" />
                                <span>Catatan dan Feedback dari Penilai</span>
                            </CardTitle>
                            <CardDescription className="text-gray-600">
                                Konstruktif dari masing-masing penilai untuk
                                pengembangan kinerja
                            </CardDescription>
                        </CardHeader>
                        <CardContent className="p-8">
                            <div className="space-y-6">
                                {penugasans?.map((penugasan, index) => {
                                    const evaluatorType =
                                        penugasan.tipe_penilai === 'atasan'
                                            ? 'Atasan'
                                            : penugasan.tipe_penilai ===
                                                'penerima_layanan'
                                              ? 'Penerima Layanan'
                                              : 'Outsourcing';

                                    const cardColor =
                                        penugasan.tipe_penilai === 'atasan'
                                            ? 'border-l-red-500 bg-red-50'
                                            : penugasan.tipe_penilai ===
                                                'penerima_layanan'
                                              ? 'border-l-blue-500 bg-blue-50'
                                              : 'border-l-green-500 bg-green-50';

                                    const badgeColor =
                                        penugasan.tipe_penilai === 'atasan'
                                            ? 'bg-red-100 text-red-800 border-red-200'
                                            : penugasan.tipe_penilai ===
                                                'penerima_layanan'
                                              ? 'bg-blue-100 text-blue-800 border-blue-200'
                                              : 'bg-green-100 text-green-800 border-green-200';

                                    return (
                                        <div
                                            key={index}
                                            className={`rounded-r-xl border-l-4 ${cardColor} p-6 shadow-lg transition-all duration-300 hover:shadow-xl`}
                                        >
                                            <div className="mb-4 flex items-start justify-between">
                                                <div className="flex items-center space-x-4">
                                                    <div className="flex-shrink-0">
                                                        <div className="flex h-12 w-12 items-center justify-center rounded-full bg-white shadow-md">
                                                            <span className="text-lg font-bold text-gray-700">
                                                                {index + 1}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h5 className="text-xl font-bold text-gray-800">
                                                            {
                                                                penugasan
                                                                    .evaluators
                                                                    ?.userable
                                                                    ?.name
                                                            }
                                                        </h5>
                                                        <div className="mt-1 flex items-center space-x-3">
                                                            <Badge
                                                                className={`${badgeColor} border font-medium`}
                                                            >
                                                                {evaluatorType}
                                                            </Badge>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div className="rounded-lg border bg-white p-4 shadow-sm">
                                                <div className="flex items-start space-x-3">
                                                    <div className="mt-1 flex-shrink-0">
                                                        <div className="flex h-8 w-8 items-center justify-center rounded-full bg-yellow-100">
                                                            <span className="text-sm text-yellow-600">
                                                                💬
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div className="flex-1">
                                                        <h6 className="mb-2 font-semibold text-gray-800">
                                                            Catatan Evaluasi:
                                                        </h6>
                                                        <p className="leading-relaxed text-gray-700 italic">
                                                            "{penugasan.catatan}
                                                            "
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    );
                                })}
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </main>
        </div>
    );
}
