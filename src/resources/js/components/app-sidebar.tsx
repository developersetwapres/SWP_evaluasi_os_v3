import { Link } from '@inertiajs/react';
import {
    BookOpen,
    Brain,
    BriefcaseBusiness,
    ChartNoAxesCombined,
    FolderGit2,
    Landmark,
    LayoutGrid,
    LucideHome,
    Network,
    Users,
} from 'lucide-react';
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
import { home } from '@/routes/administrator';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: home(),
        icon: LucideHome,
    },
    {
        title: 'User Management',
        href: indexAdministrator(),
        icon: Users,
    },
    {
        title: 'Pilar',
        href: pilarIndex(),
        icon: Landmark,
    },
    {
        title: 'Indikator',
        href: indikatorIndex(),
        icon: ChartNoAxesCombined,
    },
    {
        title: 'Behavioral',
        href: behavioralIndex(),
        icon: Brain,
    },
    {
        title: 'Kelompok Jabatan Os',
        href: kelompokJabatanIndex(),
        icon: Network,
    },
    {
        title: 'Jabatan Outsourcing',
        href: jabatanIndex(),
        icon: BriefcaseBusiness,
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
