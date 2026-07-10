<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, Link } from '@inertiajs/vue3'

const props = defineProps<{
    audit: {
        id: number
        user_id: number | null
        event: string
        auditable_type: string
        auditable_id: number
        old_values: Record<string, any>
        new_values: Record<string, any>
        url: string
        ip_address: string
        created_at: string
        user: { name: string } | null
    }
}>()

function eventoLabel(evento: string) {
    const eventos: Record<string, string> = {
        created: 'Criação',
        updated: 'Alteração',
        deleted: 'Exclusão',
    }
    return eventos[evento] ?? evento
}

function tabelaLabel(type: string) {
    const tabelas: Record<string, string> = {
        'App\\Models\\Pessoa': 'Pessoas',
        'App\\Models\\Imovel': 'Imóveis',
        'App\\Models\\User': 'Usuários',
    }
    return tabelas[type] ?? type
}
</script>

<template>
    <Head title="Detalhes da Auditoria" />
    <AppLayout :breadcrumbs="[{ title: 'Auditoria', href: '/auditoria' }, { title: 'Detalhes', href: '#' }]">
        <div class="p-6 max-w-4xl">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Detalhes da Auditoria</h1>
                <Link :href="route('auditoria.index')" class="text-gray-500 hover:underline dark:text-gray-300">
                    Voltar
                </Link>
            </div>

            <!-- Informações gerais -->
            <div class="bg-white dark:bg-gray-800 rounded border border-gray-200 dark:border-gray-700 mb-6">
                <div class="grid grid-cols-2 gap-0">
                    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                        <p class="text-xs text-gray-500 dark:text-gray-400">ID</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ audit.id }}</p>
                    </div>
                    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                        <p class="text-xs text-gray-500 dark:text-gray-400">Data e Hora</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ new Date(audit.created_at).toLocaleString('pt-BR') }}</p>
                    </div>
                    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                        <p class="text-xs text-gray-500 dark:text-gray-400">Usuário</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ audit.user?.name ?? 'Sistema' }}</p>
                    </div>
                    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                        <p class="text-xs text-gray-500 dark:text-gray-400">Evento</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ eventoLabel(audit.event) }}</p>
                    </div>
                    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                        <p class="text-xs text-gray-500 dark:text-gray-400">Tabela</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ tabelaLabel(audit.auditable_type) }}</p>
                    </div>
                    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                        <p class="text-xs text-gray-500 dark:text-gray-400">ID Auditado</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ audit.auditable_id }}</p>
                    </div>
                    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                        <p class="text-xs text-gray-500 dark:text-gray-400">URL</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white break-all">{{ audit.url }}</p>
                    </div>
                    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                        <p class="text-xs text-gray-500 dark:text-gray-400">IP</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ audit.ip_address }}</p>
                    </div>
                </div>
            </div>

            <!-- Dados anteriores -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white dark:bg-gray-800 rounded border border-gray-200 dark:border-gray-700">
                    <div class="p-3 border-b border-gray-200 dark:border-gray-700 bg-red-50 dark:bg-red-900 rounded-t">
                        <h2 class="text-sm font-semibold text-red-800 dark:text-red-200">Dados Anteriores</h2>
                    </div>
                    <div class="p-4">
                        <div v-if="Object.keys(audit.old_values).length > 0">
                            <div v-for="(value, key) in audit.old_values" :key="key" class="mb-2">
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ key }}</p>
                                <p class="text-sm text-gray-900 dark:text-white">{{ value ?? '-' }}</p>
                            </div>
                        </div>
                        <p v-else class="text-sm text-gray-500 dark:text-gray-400">Nenhum dado anterior.</p>
                    </div>
                </div>

                <!-- Dados novos -->
                <div class="bg-white dark:bg-gray-800 rounded border border-gray-200 dark:border-gray-700">
                    <div class="p-3 border-b border-gray-200 dark:border-gray-700 bg-green-50 dark:bg-green-900 rounded-t">
                        <h2 class="text-sm font-semibold text-green-800 dark:text-green-200">Dados Novos</h2>
                    </div>
                    <div class="p-4">
                        <div v-if="Object.keys(audit.new_values).length > 0">
                            <div v-for="(value, key) in audit.new_values" :key="key" class="mb-2">
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ key }}</p>
                                <p class="text-sm text-gray-900 dark:text-white">{{ value ?? '-' }}</p>
                            </div>
                        </div>
                        <p v-else class="text-sm text-gray-500 dark:text-gray-400">Nenhum dado novo.</p>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
