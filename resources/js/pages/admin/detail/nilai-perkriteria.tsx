'use client';

import { EmployeeHeader } from '@/components/penilaian/detail/employee-header';
import { EmployeeNavigation } from '@/components/penilaian/detail/employee-navigation';
import { ViewScoreComponent } from '@/components/penilaian/view-score-component';
import { cn } from '@/lib/utils';
import { nilaiPerkriteria } from '@/routes/os';
import { Link, usePage } from '@inertiajs/react';
import { User2 } from 'lucide-react';

export default function nilaiPerItemKriteria({
    rekapPerAspek,
    evaluationData,
    uuidOs,
}: any) {
    const { url } = usePage();
    const isActive = (path: string) => url == path;

    return (
        <div className="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
            <EmployeeHeader />

            <main className="mx-auto max-w-7xl px-4 pb-8 sm:px-6 lg:px-8">
                <div className="space-y-8">
                    <EmployeeNavigation employeeUuid={uuidOs} />

                    <nav className="grid h-10 w-full grid-cols-3 rounded-lg bg-muted p-1">
                        <Link
                            href={nilaiPerkriteria.url({
                                outsourcing: uuidOs,
                            })}
                            className={cn(
                                'flex items-center justify-center space-x-2 rounded-md px-3 py-2 text-sm font-medium transition-all',
                                isActive(
                                    nilaiPerkriteria.url({
                                        outsourcing: uuidOs,
                                    }),
                                )
                                    ? 'bg-background text-foreground shadow-sm'
                                    : 'text-muted-foreground hover:bg-background/50 hover:text-foreground',
                            )}
                        >
                            <User2 className="h-4 w-4" />
                            <span className="hidden sm:inline">Atasan</span>
                        </Link>

                        <Link
                            href={nilaiPerkriteria.url({
                                outsourcing: uuidOs,
                                tipePenilai: 'penerima_layanan',
                            })}
                            className={cn(
                                'flex items-center justify-center space-x-2 rounded-md px-3 py-2 text-sm font-medium transition-all',
                                isActive(
                                    nilaiPerkriteria.url({
                                        outsourcing: uuidOs,
                                        tipePenilai: 'penerima_layanan',
                                    }),
                                )
                                    ? 'bg-background text-foreground shadow-sm'
                                    : 'text-muted-foreground hover:bg-background/50 hover:text-foreground',
                            )}
                        >
                            <User2 className="h-4 w-4" />
                            <span className="hidden sm:inline">
                                Penerima Layanan
                            </span>
                        </Link>

                        <Link
                            href={nilaiPerkriteria.url({
                                outsourcing: uuidOs,
                                tipePenilai: 'teman_setingkat',
                            })}
                            className={cn(
                                'flex items-center justify-center space-x-2 rounded-md px-3 py-2 text-sm font-medium transition-all',
                                isActive(
                                    nilaiPerkriteria.url({
                                        outsourcing: uuidOs,
                                        tipePenilai: 'teman_setingkat',
                                    }),
                                )
                                    ? 'bg-background text-foreground shadow-sm'
                                    : 'text-muted-foreground hover:bg-background/50 hover:text-foreground',
                            )}
                        >
                            <User2 className="h-4 w-4" />
                            <span className="hidden sm:inline">
                                Teman Setingkat
                            </span>
                        </Link>
                    </nav>

                    <ViewScoreComponent
                        rekapPerAspek={rekapPerAspek}
                        evaluationData={evaluationData}
                    />
                </div>
            </main>
        </div>
    );
}
