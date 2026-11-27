import { Link } from '@inertiajs/react';
import { BookOpen, FolderGit2, LayoutGrid } from 'lucide-react';
import AppLogo from '@/components/app-logo';
import { NavMain } from '@/components/nav-main';
import { indexAdministrator } from '@/routes/user';
import {
    Sidebar,
    SidebarContent,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import type { NavItem } from '@/types';

const mainNavItems: NavItem[] = [
    {
        title: 'User Management',
        href: indexAdministrator(),
        icon: LayoutGrid,
    },
    {
        title: 'Pilar',
        href: '#',
        icon: BookOpen,
    },
    {
        title: 'Indikator',
        href: '#',
        icon: FolderGit2,
    },
    {
        title: 'Behavioral',
        href: '#',
        icon: FolderGit2,
    },
    {
        title: 'Kelompok Jabatan Os',
        href: '#',
        icon: FolderGit2,
    },
    {
        title: 'Jabatan Outsourcing',
        href: '#',
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
