'use client';

import {
    Card,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import AdminLayout from '@/layouts/app/app-adminkmz-layout';
import { BarChart3 } from 'lucide-react';

export default function ResultsRecapPage() {
    return (
        <AdminLayout>
            <div className="space-y-6">
                {/* Header Card */}
                <Card className="bg-gradient-to-r from-purple-500 to-indigo-600 text-white">
                    <CardHeader>
                        <CardTitle className="flex items-center space-x-2 text-2xl">
                            <BarChart3 className="h-6 w-6" />
                            <span>Ringkasan Evaluasi</span>
                        </CardTitle>
                        <CardDescription className="text-purple-100">
                            Lihat hasil akhir evaluasi dari berbagai penilai,
                            termasuk skor total dan grafik performa.
                        </CardDescription>
                    </CardHeader>
                </Card>
            </div>
        </AdminLayout>
    );
}
