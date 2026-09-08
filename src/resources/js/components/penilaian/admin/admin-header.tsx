'use client';

import { useMobileNavigation } from '@/hooks/use-mobile-navigation';
import { logout } from '@/routes';
import { Link, router } from '@inertiajs/react';
import { LogOut, Settings } from 'lucide-react';

export function AdminHeader() {
    const cleanup = useMobileNavigation();

    const handleLogout = () => {
        cleanup();
        router.flushAll();
    };

    return (
        <header className="border-b bg-white shadow-sm">
            <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div className="flex items-center justify-between py-4">
                    <div className="flex items-center space-x-3">
                        <div className="rounded-lg bg-blue-600 p-2">
                            <Settings className="h-6 w-6 text-white" />
                        </div>
                        <div>
                            <h1 className="text-xl font-bold text-gray-900">
                                Dashboard Administrator
                            </h1>
                            <p className="text-sm text-gray-500">
                                Sistem Penilaian Kinerja Outsourcing
                            </p>
                        </div>
                    </div>
                    <Link
                        className="flex inline-flex items-center justify-center gap-2 space-x-2 rounded-md border bg-transparent px-3 py-2 text-sm font-medium whitespace-nowrap hover:border-red-200 hover:bg-red-50 hover:text-red-600"
                        href={logout()}
                        onClick={handleLogout}
                        data-test="logout-button"
                    >
                        <LogOut className="h-4 w-4" />
                        Log out
                    </Link>
                </div>
            </div>
        </header>
    );
}
