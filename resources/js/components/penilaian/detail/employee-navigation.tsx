'use client';

import { Button } from '@/components/ui/button';
import { Link } from '@inertiajs/react';
import { FileText, MessageCircle, Users } from 'lucide-react';

import {
    catatanEvaluator,
    detailperaspek,
    nilaiPerkriteria,
    rekapaspekevaluator,
} from '@/routes/os';
import { usePage } from '@inertiajs/react';

interface EmployeeNavigationProps {
    employeeUuid: string;
}

export function EmployeeNavigation({ employeeUuid }: EmployeeNavigationProps) {
    const { url } = usePage();
    const isActive = (path: string) => url == path;

    const nav = [
        {
            link: rekapaspekevaluator.url(employeeUuid),
            title: 'Nilai Akhir',
            icon: FileText,
        },
        {
            link: detailperaspek.url(employeeUuid),
            title: 'Rekap Nilai',
            icon: Users,
        },
        {
            link: catatanEvaluator.url(employeeUuid),
            title: 'Catatan Evaluator',
            icon: MessageCircle,
        },
        {
            link: nilaiPerkriteria.url({
                outsourcing: employeeUuid,
            }),
            title: 'Nilai Perkriteria',
            icon: MessageCircle,
        },
    ];

    return (
        <div className="mb-5 grid w-full grid-cols-4 rounded-lg border border-gray-200 bg-white p-1 shadow-sm">
            {nav.map((navigasi, index) => {
                const Icon = navigasi.icon;

                return (
                    <Link href={navigasi.link} className="contents" key={index}>
                        <Button
                            variant="ghost"
                            className={`flex items-center space-x-2 rounded-md ${
                                isActive(navigasi.link)
                                    ? 'bg-indigo-500 text-white hover:bg-indigo-600 hover:text-white'
                                    : 'hover:bg-gray-100'
                            }`}
                        >
                            <Icon className="h-4 w-4" />
                            <span className="hidden sm:inline">
                                {navigasi.title}
                            </span>
                        </Button>
                    </Link>
                );
            })}
        </div>
    );
}
