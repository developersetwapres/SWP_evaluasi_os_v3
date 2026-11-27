import { AdminHeader } from '@/components/penilaian/admin/admin-header';
import { AdminNav } from '@/components/penilaian/admin/admin-nav';
import { AdminProfileCard } from '@/components/penilaian/admin/admin-profile-card';
import type React from 'react';

export default function AdminLayout({
    children,
}: {
    children: React.ReactNode;
}) {
    return (
        <div className="min-h-screen bg-linear-to-br from-blue-50 to-indigo-100">
            <AdminHeader />
            <main className="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
                <div className="space-y-8">
                    <AdminProfileCard />
                    <div className="space-y-6">
                        <AdminNav />
                        {children}
                    </div>
                </div>
            </main>
        </div>
    );
}
