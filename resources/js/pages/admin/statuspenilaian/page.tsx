'use client';

import { Badge } from '@/components/ui/badge';
import {
    Card,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import AdminLayout from '@/layouts/app/app-adminkmz-layout';
import { evaluators, outsourcings } from '@/routes/penugasan';
import { Link } from '@inertiajs/react';
import { Download, User, Users2 } from 'lucide-react';
import { useMemo, useState } from 'react';

export default function StatusPenilaian({
    byOutsourcings,
    byEvaluators,
}: {
    byOutsourcings: any;
    byEvaluators: any;
}) {
    console.log(byOutsourcings);

    const [evaluatorFilter, setEvaluatorFilter] = useState<
        'all' | 'completed' | 'incomplete'
    >('all');
    const [outsourcingFilter, setOutsourcingFilter] = useState<
        'all' | 'completed' | 'incomplete'
    >('all');

    const filteredEvaluators = useMemo(() => {
        if (!byEvaluators) return [];
        if (evaluatorFilter === 'all') return byEvaluators;
        return byEvaluators.filter((r: any) =>
            evaluatorFilter === 'completed'
                ? r.status === 'completed'
                : r.status !== 'completed',
        );
    }, [byEvaluators, evaluatorFilter]);

    const filteredOutsourcings = useMemo(() => {
        if (!byOutsourcings) return [];
        return byOutsourcings.filter((r: any) => {
            if (outsourcingFilter === 'all') return true;
            const statuses = [
                r.evaluatorsAtasan?.status,
                r.evaluatorsPenerimaLayanan?.status,
                r.evaluatorsTemanSetingkat?.status,
            ];
            const allCompleted = statuses.every((s) => s === 'completed');
            return outsourcingFilter === 'completed'
                ? allCompleted
                : !allCompleted;
        });
    }, [byOutsourcings, outsourcingFilter]);

    return (
        <AdminLayout>
            <div className="space-y-6">
                {/* Header Card */}
                <Card className="bg-gradient-to-r from-purple-500 to-indigo-600 text-white">
                    <CardHeader>
                        <CardTitle className="flex items-center space-x-2 text-2xl">
                            <Users2 className="h-6 w-6" />
                            <span>Status Penilaian</span>
                        </CardTitle>
                        <CardDescription className="text-purple-100">
                            Rekap status penilaian oleh by evaluators dan by
                            outsourcings.
                        </CardDescription>
                    </CardHeader>
                </Card>

                <Card>
                    <CardHeader>
                        <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                            <div>
                                <CardTitle>Status Penilaian</CardTitle>
                                <CardDescription>
                                    Ringkasan status penilaian dalam dua format.
                                </CardDescription>
                            </div>

                            <div className="flex gap-2 md:justify-end">
                                <Link
                                    className="flex items-center gap-2 rounded-md bg-gray-900 px-4 py-1.5 text-sm text-white hover:bg-black"
                                    href={evaluators.url()}
                                >
                                    <Download className="h-4 w-4" />
                                    <span>Export by Evaluator</span>
                                </Link>

                                <Link
                                    className="flex items-center gap-2 rounded-md bg-gray-900 px-4 py-1.5 text-sm text-white hover:bg-black"
                                    href={outsourcings.url()}
                                >
                                    <Download className="h-4 w-4" />
                                    <span>Export by Outsourcing</span>
                                </Link>
                            </div>
                        </div>
                    </CardHeader>

                    <div className="p-4">
                        <Tabs defaultValue="by-evaluator">
                            <TabsList>
                                <TabsTrigger
                                    value="by-evaluator"
                                    className="flex items-center gap-2"
                                >
                                    <User className="h-4 w-4" />
                                    By Evaluator
                                </TabsTrigger>
                                <TabsTrigger
                                    value="by-outsourcing"
                                    className="flex items-center gap-2"
                                >
                                    <Users2 className="h-4 w-4" />
                                    By Outsourcing
                                </TabsTrigger>
                            </TabsList>

                            <TabsContent value="by-evaluator">
                                <div className="mb-4 flex justify-end">
                                    <div className="w-48">
                                        <Select
                                            value={evaluatorFilter}
                                            onValueChange={(v) =>
                                                setEvaluatorFilter(v as any)
                                            }
                                        >
                                            <SelectTrigger className="w-full">
                                                <SelectValue placeholder="Filter Status" />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectItem value="all">
                                                    Semua Status
                                                </SelectItem>
                                                <SelectItem value="completed">
                                                    Completed
                                                </SelectItem>
                                                <SelectItem value="incomplete">
                                                    Incomplete
                                                </SelectItem>
                                            </SelectContent>
                                        </Select>
                                    </div>
                                </div>

                                <Table>
                                    <TableHeader>
                                        <TableRow>
                                            <TableHead>No</TableHead>
                                            <TableHead>Evaluator</TableHead>
                                            <TableHead>Tipe Penilai</TableHead>
                                            <TableHead>Status</TableHead>
                                            <TableHead>
                                                Outsourcing Yang dinilai
                                            </TableHead>
                                            <TableHead>
                                                Jabatan Yang dinilai
                                            </TableHead>
                                        </TableRow>
                                    </TableHeader>
                                    <TableBody>
                                        {filteredEvaluators.map(
                                            (row: any, index: number) => (
                                                <TableRow key={row.index}>
                                                    <TableCell className="w-8">
                                                        {index + 1}
                                                    </TableCell>
                                                    <TableCell>
                                                        {row.evaluator_name}
                                                    </TableCell>
                                                    <TableCell>
                                                        {row.tipe_penilai}
                                                    </TableCell>
                                                    <TableCell>
                                                        <Badge
                                                            className={
                                                                row.status ===
                                                                'completed'
                                                                    ? 'bg-green-500 text-white'
                                                                    : 'bg-red-500 text-white'
                                                            }
                                                        >
                                                            {row.status}
                                                        </Badge>
                                                    </TableCell>
                                                    <TableCell>
                                                        {row.outsourcing_name}
                                                    </TableCell>
                                                    <TableCell>
                                                        {
                                                            row.outsourcing_jabatan
                                                        }
                                                    </TableCell>
                                                </TableRow>
                                            ),
                                        )}
                                    </TableBody>
                                </Table>
                            </TabsContent>

                            <TabsContent value="by-outsourcing">
                                <div className="mb-4 flex justify-end">
                                    <div className="w-48">
                                        <Select
                                            value={outsourcingFilter}
                                            onValueChange={(v) =>
                                                setOutsourcingFilter(v as any)
                                            }
                                        >
                                            <SelectTrigger className="w-full">
                                                <SelectValue placeholder="Filter Status" />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectItem value="all">
                                                    Semua Status
                                                </SelectItem>
                                                <SelectItem value="completed">
                                                    Completed
                                                </SelectItem>
                                                <SelectItem value="incomplete">
                                                    Incomplete
                                                </SelectItem>
                                            </SelectContent>
                                        </Select>
                                    </div>
                                </div>

                                <Table>
                                    <TableHeader>
                                        <TableRow>
                                            <TableHead>No</TableHead>
                                            <TableHead>Outsourcing</TableHead>
                                            <TableHead>Jabatan</TableHead>
                                            <TableHead>
                                                Evaluator Atasan
                                            </TableHead>
                                            <TableHead>
                                                Status Evaluator Atasan
                                            </TableHead>
                                            <TableHead>
                                                Evaluator Penerima Layanan
                                            </TableHead>
                                            <TableHead>
                                                Status Evaluator Penerima
                                                Layanan
                                            </TableHead>
                                            <TableHead>
                                                Evaluator Teman Setingkat
                                            </TableHead>
                                            <TableHead>
                                                Status Evaluator Teman Setingkat
                                            </TableHead>
                                        </TableRow>
                                    </TableHeader>
                                    <TableBody>
                                        {filteredOutsourcings.map(
                                            (row: any, index: number) => (
                                                <TableRow key={index}>
                                                    <TableCell className="w-8">
                                                        {index + 1}
                                                    </TableCell>
                                                    <TableCell>
                                                        {row.outsourcing_name}
                                                    </TableCell>
                                                    <TableCell>
                                                        {
                                                            row.outsourcing_jabatan
                                                        }
                                                    </TableCell>
                                                    <TableCell>
                                                        {
                                                            row.evaluatorsAtasan
                                                                .name
                                                        }
                                                    </TableCell>
                                                    <TableCell>
                                                        <Badge
                                                            className={
                                                                row
                                                                    .evaluatorsAtasan
                                                                    .status ===
                                                                'completed'
                                                                    ? 'bg-green-500 text-white'
                                                                    : 'bg-red-500 text-white'
                                                            }
                                                        >
                                                            {
                                                                row
                                                                    .evaluatorsAtasan
                                                                    ?.status
                                                            }
                                                        </Badge>
                                                    </TableCell>
                                                    <TableCell>
                                                        {
                                                            row
                                                                .evaluatorsPenerimaLayanan
                                                                ?.name
                                                        }
                                                    </TableCell>
                                                    <TableCell>
                                                        <Badge
                                                            className={
                                                                row
                                                                    .evaluatorsPenerimaLayanan
                                                                    ?.status ===
                                                                'completed'
                                                                    ? 'bg-green-500 text-white'
                                                                    : 'bg-red-500 text-white'
                                                            }
                                                        >
                                                            {
                                                                row
                                                                    .evaluatorsPenerimaLayanan
                                                                    ?.status
                                                            }
                                                        </Badge>
                                                    </TableCell>
                                                    <TableCell>
                                                        {
                                                            row
                                                                .evaluatorsTemanSetingkat
                                                                ?.name
                                                        }
                                                    </TableCell>
                                                    <TableCell>
                                                        <Badge
                                                            className={
                                                                row
                                                                    .evaluatorsTemanSetingkat
                                                                    ?.status ===
                                                                'completed'
                                                                    ? 'bg-green-500 text-white'
                                                                    : 'bg-red-500 text-white'
                                                            }
                                                        >
                                                            {
                                                                row
                                                                    .evaluatorsTemanSetingkat
                                                                    ?.status
                                                            }
                                                        </Badge>
                                                    </TableCell>
                                                </TableRow>
                                            ),
                                        )}
                                    </TableBody>
                                </Table>
                            </TabsContent>
                        </Tabs>
                    </div>
                </Card>
            </div>
        </AdminLayout>
    );
}
