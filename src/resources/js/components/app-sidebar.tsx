import { Link } from '@inertiajs/react';
import { BookOpen, FolderGit2, LayoutGrid } from 'lucide-react';
import AppLogo from '@/components/app-logo';
import { NavMain } from '@/components/nav-main';
import {
    Sidebar,
    SidebarContent,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import { index as behavioralIndex } from '@/routes/behavioral';
import { index as indikatorIndex } from '@/routes/indikator';
import { index as jabatanIndex } from '@/routes/jabatan';
import { index as kelompokJabatanIndex } from '@/routes/kelompokJabatan';
import { index as pilarIndex } from '@/routes/pilar';
import { indexAdministrator } from '@/routes/user';
import type { NavItem } from '@/types';

const mainNavItems: NavItem[] = [
    {
        title: 'User Management',
        href: indexAdministrator(),
        icon: LayoutGrid,
    },
    {
        title: 'Pilar',
        href: pilarIndex(),
        icon: BookOpen,
    },
    {
        title: 'Indikator',
        href: indikatorIndex(),
        icon: FolderGit2,
    },
    {
        title: 'Behavioral',
        href: behavioralIndex(),
        icon: FolderGit2,
    },
    {
        title: 'Kelompok Jabatan Os',
        href: kelompokJabatanIndex(),
        icon: FolderGit2,
    },
    {
        title: 'Jabatan Outsourcing',
        href: jabatanIndex(),
        icon: FolderGit2,
    },
];

export function AppSidebar() {
    return (
        <Sidebar collapsible="icon" variant="inset">
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton size="lg" asChild>
                            <Link href={dashboard()} prefetch>
                                <AppLogo />
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>

            <SidebarContent>
                <NavMain items={mainNavItems} />
            </SidebarContent>
        </Sidebar>
    );
}
