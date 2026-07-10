<script setup lang="ts">
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { Building2, ClipboardList, Home, Settings, Users, UsersRound } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';
import { computed } from 'vue';

const page = usePage()
const user = computed(() => (page.props.auth as any)?.user)
const perfil = computed(() => user.value?.perfil ?? 'A')

const todosItens = [
    {
        title: 'Home',
        url: '/dashboard',
        icon: Home,
        perfis: null,
    },
    {
        title: 'Pessoas',
        url: '/pessoas',
        icon: UsersRound,
        perfis: null,
    },
    {
        title: 'Imóveis',
        url: '/imoveis',
        icon: Building2,
        perfis: null,
    },
    {
        title: 'Usuários',
        url: '/usuarios',
        icon: Users,
        perfis: ['T', 'S'],
    },
    {
        title: 'Auditoria',
        url: '/auditoria',
        icon: ClipboardList,
        perfis: ['T'],
    },
    {
        title: 'Configurações',
        url: '/settings/profile',
        icon: Settings,
        perfis: null,
    },
];

const mainNavItems = computed(() =>
    todosItens.filter(item =>
        item.perfis === null || item.perfis.includes(perfil.value)
    )
)
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('dashboard')">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
