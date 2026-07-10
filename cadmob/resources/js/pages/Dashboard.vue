<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { Building2, UsersRound, Users } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

defineProps<{
    name?: string;
}>();
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">
                Bem-vindo ao CadImob
            </h1>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <Link href="/pessoas"
                    class="flex items-center gap-4 p-6 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-lg">
                        <UsersRound class="w-6 h-6 text-blue-600 dark:text-blue-300" />
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Módulo</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">Pessoas</p>
                    </div>
                </Link>

                <Link href="/imoveis"
                    class="flex items-center gap-4 p-6 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    <div class="bg-green-100 dark:bg-green-900 p-3 rounded-lg">
                        <Building2 class="w-6 h-6 text-green-600 dark:text-green-300" />
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Módulo</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">Imóveis</p>
                    </div>
                </Link>

                <Link href="/usuarios"
                    class="flex items-center gap-4 p-6 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    <div class="bg-purple-100 dark:bg-purple-900 p-3 rounded-lg">
                        <Users class="w-6 h-6 text-purple-600 dark:text-purple-300" />
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Módulo</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">Usuários</p>
                    </div>
                </Link>
            </div>
        </div>
    </AppLayout>
</template>
