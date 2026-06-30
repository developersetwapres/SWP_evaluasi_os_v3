'use client';

import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import AdminLayout from '@/layouts/app/app-adminkmz-layout';
import { Link } from '@inertiajs/react';
import { BarChart3, Download, Eye, Search } from 'lucide-react';
import { useState } from 'react';

export default function ResultsRecapPage() {
    const [searchTerm, setSearchTerm] = useState('');
    const [filterUnit, setFilterUnit] = useState('all');

    return (
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

            <Card>
                <CardHeader>
                    <CardTitle>Rekap Hasil Penilaian</CardTitle>
                    <CardDescription>
                        Ringkasan nilai kinerja hasil akumulasi penilaian dari
                        seluruh evaluator.
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    {/* Filters */}
                    <div className="mb-6 flex flex-col gap-4 sm:flex-row">
                        <div className="relative flex-1">
                            <Search className="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 transform text-gray-400" />
                            <Input
                                placeholder="Cari pegawai atau unit..."
                                value={searchTerm}
                                onChange={(e) => setSearchTerm(e.target.value)}
                                className="pl-10"
                            />
                        </div>
                        <Select
                            value={filterUnit}
                            onValueChange={setFilterUnit}
                        >
                            <SelectTrigger className="w-full sm:w-48">
                                <SelectValue placeholder="Filter Unit" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="all">Semua Unit</SelectItem>
                            </SelectContent>
                        </Select>
                        <Button className="flex items-center space-x-2">
                            <Download className="h-4 w-4" />
                            <span>Export</span>
                        </Button>
                    </div>

                    {/* Results Grid */}
                    <Card className="py-12 text-center">
                        <CardContent>
                            <BarChart3 className="mx-auto mb-4 h-12 w-12 text-gray-400" />
                            <h3 className="mb-2 text-lg font-medium text-gray-900">
                                Tidak ada hasil ditemukan
                            </h3>
                            <p className="text-gray-500">
                                Coba ubah filter atau kata kunci pencarian
                            </p>
                        </CardContent>
                    </Card>
                </CardContent>
            </Card>
        </div>
    );
}
