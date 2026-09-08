'use client';

import { Button } from '@/components/ui/button';
import { Link } from '@inertiajs/react';
import { ArrowLeft, LogOut, Settings } from 'lucide-react';

interface EmployeeHeaderProps {
    onLogout?: () => void;
}

export function EmployeeHeader() {
    return (
        <>
            <div className="mx-auto max-w-7xl px-4 pt-6 sm:px-6 lg:px-8">
                <Link href="/dashboard">
                    <Button
                        variant="ghost"
                        className="mb-4 flex items-center space-x-2 border"
                    >
                        <ArrowLeft className="h-4 w-4" />
                        <span>Kembali</span>
                    </Button>
                </Link>
            </div>
        </>
    );
}
