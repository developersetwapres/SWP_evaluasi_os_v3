'use client';

import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import {
    Card,
    CardContent,
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
import { Tooltip } from '@/components/ui/tooltip';
import AdminLayout from '@/layouts/app/app-adminkmz-layout';
import { Trophy } from 'lucide-react';
import { useEffect, useMemo, useState } from 'react';

const slugify = (str: string) =>
    str
        .toLowerCase()
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/(^-|-$)/g, '');

export default function SaranPerbaikan({ Outsourcings }: any) {
    const positions = useMemo(() => {
        if (!Array.isArray(Outsourcings)) return [];
        return Outsourcings.map((d: any) => ({
            value: slugify(d.jabatan || ''),
            label: d.jabatan,
        }));
    }, [Outsourcings]);

    const [selectedPosition, setSelectedPosition] = useState('');

    useEffect(() => {
        if (!selectedPosition && positions.length > 0) {
            setSelectedPosition(positions[0].value);
        }
    }, [positions, selectedPosition]);

    const entries = useMemo(() => {
        const groups = Array.isArray(Outsourcings) ? Outsourcings : [];

        const list: any[] = [];

        groups.forEach((g: any) => {
            const jabatan = g.jabatan || '';

            (g.saran || []).forEach((s: any) => {
                list.push({
                    id: `${jabatan}-${s.name}`,
                    name: s.name,
                    image: s.image,
                    jabatan,
                    penugasan: s.penugasan || [],
                });
            });
        });

        return list;
    }, [Outsourcings]);

    console.log(entries);

    return (
        <AdminLayout>
            <div className="space-y-6">
                <Card className="bg-gradient-to-r from-indigo-500 to-purple-600 text-white">
                    <CardHeader>
                        <CardTitle className="text-2xl">
                            Saran Perbaikan Outsourcing
                        </CardTitle>
                        <CardDescription className="text-indigo-100">
                            Dashboard penilaian kinerja outsourcing berdasarkan
                            saran perbaikan dari evaluator.
                        </CardDescription>
                    </CardHeader>
                </Card>

                <Card>
                    <CardHeader>
                        <div className="flex items-center space-x-2">
                            <Trophy className="h-4 w-4 text-muted-foreground" />
                            <Select
                                value={selectedPosition}
                                onValueChange={setSelectedPosition}
                            >
                                <SelectTrigger className="w-72">
                                    <SelectValue placeholder="Pilih Jabatan Outsourcing" />
                                </SelectTrigger>
                                <SelectContent>
                                    {positions.map((p: any) => (
                                        <SelectItem
                                            key={p.value}
                                            value={p.value}
                                        >
                                            {p.label}
                                        </SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>
                        </div>
                    </CardHeader>

                    <CardContent>
                        <div className="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                            {entries
                                .filter((p: any) => {
                                    if (!selectedPosition) return true;
                                    return (
                                        slugify(p.jabatan || '') ===
                                        selectedPosition
                                    );
                                })
                                .map((person: any) => {
                                    return (
                                        <Card
                                            className="gap-0 bg-gray-50 p-4"
                                            key={person.id}
                                        >
                                            <div className="mb-4 flex items-start gap-3">
                                                <Avatar className="h-13 w-13">
                                                    <AvatarImage
                                                        src={
                                                            `/storage/${person.image}` ||
                                                            '/placeholder.svg'
                                                        }
                                                        alt="Preview"
                                                    />
                                                    <AvatarFallback>
                                                        {person.name
                                                            ? person.name
                                                                  .split(' ')
                                                                  .map(
                                                                      (
                                                                          n: any,
                                                                      ) => n[0],
                                                                  )
                                                                  .join('')
                                                                  .substring(
                                                                      0,
                                                                      2,
                                                                  )
                                                                  .toUpperCase()
                                                            : 'U'}
                                                    </AvatarFallback>
                                                </Avatar>

                                                <div className="my-auto flex-1">
                                                    <div className="flex items-center justify-between">
                                                        <div>
                                                            <h4 className="text-sm leading-5 font-semibold">
                                                                {person.name}
                                                            </h4>
                                                            <p className="text-xs text-muted-foreground">
                                                                {person.jabatan}
                                                            </p>
                                                        </div>

                                                        <div className="flex items-center gap-2">
                                                            <div className="text-right text-xs text-muted-foreground">
                                                                {person.unit}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {/* Timeline-like list */}
                                            <div className="space-y-3">
                                                {(person.penugasan || [])
                                                    .length === 0 && (
                                                    <div className="text-sm text-muted-foreground">
                                                        Belum ada saran.
                                                    </div>
                                                )}

                                                {(person.penugasan || []).map(
                                                    (m: any, idx: number) => {
                                                        return (
                                                            <Card
                                                                key={idx}
                                                                className="items-start gap-1 rounded-xl border bg-slate-50 bg-white p-4 dark:bg-slate-800"
                                                            >
                                                                <div className="flex items-center gap-3">
                                                                    <Avatar className="h-12 w-12">
                                                                        <AvatarImage
                                                                            src={
                                                                                `/storage/${m.image}` ||
                                                                                '/placeholder.svg'
                                                                            }
                                                                            alt="Preview"
                                                                        />
                                                                        <AvatarFallback>
                                                                            {String(
                                                                                person.name ||
                                                                                    'U',
                                                                            )
                                                                                .charAt(
                                                                                    0,
                                                                                )
                                                                                .toUpperCase()}
                                                                        </AvatarFallback>
                                                                    </Avatar>

                                                                    <div className="flex-1">
                                                                        <div className="items-center justify-between">
                                                                            <div className="text-sm font-medium">
                                                                                {
                                                                                    m.nama
                                                                                }
                                                                            </div>
                                                                            <Tooltip>
                                                                                <span className="rounded-full bg-muted px-2 py-0.5 text-xs text-muted-foreground">
                                                                                    {
                                                                                        m.tipe_penilai
                                                                                    }
                                                                                </span>
                                                                            </Tooltip>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <p className="m-2 text-sm text-slate-700 dark:text-slate-300">
                                                                    {m.catatan}
                                                                </p>
                                                            </Card>
                                                        );
                                                    },
                                                )}
                                            </div>
                                        </Card>
                                    );
                                })}
                        </div>
                    </CardContent>
                </Card>
            </div>
        </AdminLayout>
    );
}
