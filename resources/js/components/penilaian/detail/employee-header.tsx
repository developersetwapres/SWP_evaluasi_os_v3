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

                        <Button
                            variant="outline"
                            // onClick={onLogout}
                            className="flex items-center space-x-2 bg-transparent hover:border-red-200 hover:bg-red-50 hover:text-red-600"
                        >
                            <LogOut className="h-4 w-4" />
                            <span>Logout</span>
                        </Button>
                    </div>
                </div>
            </header>

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
