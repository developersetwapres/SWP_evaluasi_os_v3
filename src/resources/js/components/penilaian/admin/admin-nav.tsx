'use client';

import { index as indexPenugasan } from '@/actions/App/Http/Controllers/PenugasanController';
import { cn } from '@/lib/utils';
import { dashboard } from '@/routes';
import { ranking, saranEvaluator } from '@/routes/os';
import { statuspenilaian } from '@/routes/penugasan';
import { index as userIndex } from '@/routes/user';
import { Link, usePage } from '@inertiajs/react';
import {
    BarChart3,
    ClipboardCheck,
    MessageSquareText,
    Trophy,
    UserCog,
    UsersRound,
} from 'lucide-react';

export function AdminNav() {
    const { url } = usePage();

    const navItems = [
        {
            href: dashboard.url(),
            label: 'Rekap Hasil',
            icon: BarChart3,
        },
        {
            href: ranking.url(),
            label: 'Ranking Skor',
            icon: Trophy,
        },
        {
            href: saranEvaluator.url(),
            label: 'Saran Evaluator',
            icon: MessageSquareText,
        },
        {
            href: indexPenugasan.url(),
            label: 'Penugasan Peer',
            icon: UsersRound,
        },
        {
            href: statuspenilaian.url(),
            label: 'Status Penilaian',
            icon: ClipboardCheck,
        },
        {
            href: userIndex.url('outsourcings'),
            label: 'Kelola User',
            icon: UserCog,
        },
    ];

    return (
        <nav className={`grid h-12 w-full grid-cols-6 rounded-lg bg-muted p-1`}>
            {navItems.map((item) => {
                const Icon = item.icon;
                const pathname = url; // mirip usePathname()
                const isActive = pathname === item.href;

                return (
                    <Link
                        href={item.href}
                        key={item.href}
                        className={cn(
                            'flex items-center justify-center space-x-2 rounded-md px-3 py-2 text-sm font-medium transition-all',
                            isActive
                                ? 'bg-background text-foreground shadow-sm'
                                : 'text-muted-foreground hover:bg-background/50 hover:text-foreground',
                        )}
                    >
                        <Icon className="h-4 w-4" />
                        <span className="hidden sm:inline">{item.label}</span>
                    </Link>
                );
            })}
        </nav>
    );
}
