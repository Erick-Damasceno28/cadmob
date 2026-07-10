<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, Link, router, usePage } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

const props = defineProps<{
    audits: {
        data: Array<{
            id: number
            user_id: number | null
            event: string
            auditable_type: string
            auditable_id: number
            created_at: string
            user: { name: string } | null
        }>
        current_page: number
        last_page: number
        next_page_url: string | null
        prev_page_url: string | null
    }
    filtros: {
        usuario?: string
        evento?: string
        data?: string
        tabela?: string
    }
}>()

const page = usePage()
const flash = computed(() => page.props.flash as { success?: string })

const filtro = ref({
    usuario: props.filtros.usuario ?? '',
    evento: props.filtros.evento ?? '',
    data: props.filtros.data ?? '',
    tabela: props.filtros.tabela ?? '',
})

function buscar() {
    router.get(route('auditoria.index'), filtro.value, {
        preserveState: true,
        replace: true,
    })
}

function limpar() {
    filtro.value = { usuario: '', evento: '', data: '', tabela: '' }
    buscar()
}

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

function eventoClasse(evento: string) {
    const classes: Record<string, string> = {
        created: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
        updated: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
        deleted: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
    }
    return classes[evento] ?? 'bg-gray-100 text-gray-800'
}
</script>

<template>
    <Head title="Auditoria" />
    <AppLayout :breadcrumbs="[{ title: 'Auditoria', href: '/auditoria' }]">
        <div class="p-6">

            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Auditoria</h1>
            </div>

            <!-- Filtros -->
            <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded mb-4 border border-gray-200 dark:border-gray-700">
                <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Filtros</h2>
                <div class="grid grid-cols-2 gap-3 md:grid-cols-4">
                    <div>
                        <label class="block text-xs font-medium mb-1 text-gray-600 dark:text-gray-400">Usuário</label>
                        <input v-model="filtro.usuario" type="text" placeholder="Nome do usuário..."
                            class="w-full border rounded px-2 py-1 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600" />
                    </div>
                    <div>
                        <label class="block text-xs font-medium mb-1 text-gray-600 dark:text-gray-400">Evento</label>
                        <select v-model="filtro.evento"
                            class="w-full border rounded px-2 py-1 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600">
                            <option value="">Todos</option>
                            <option value="created">Criação</option>
                            <option value="updated">Alteração</option>
                            <option value="deleted">Exclusão</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium mb-1 text-gray-600 dark:text-gray-400">Data</label>
                        <input v-model="filtro.data" type="date"
                            class="w-full border rounded px-2 py-1 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600" />
                    </div>
                    <div>
                        <label class="block text-xs font-medium mb-1 text-gray-600 dark:text-gray-400">Tabela</label>
                        <select v-model="filtro.tabela"
                            class="w-full border rounded px-2 py-1 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600">
                            <option value="">Todas</option>
                            <option value="Pessoa">Pessoas</option>
                            <option value="Imovel">Imóveis</option>
                            <option value="User">Usuários</option>
                        </select>
                    </div>
                </div>
                <div class="flex gap-2 mt-3">
                    <button @click="buscar"
                        class="bg-blue-600 text-white px-4 py-1 rounded hover:bg-blue-700 text-sm">
                        Buscar
                    </button>
                    <button @click="limpar"
                        class="bg-gray-400 text-white px-4 py-1 rounded hover:bg-gray-500 text-sm">
                        Limpar
                    </button>
                </div>
            </div>

            <table class="w-full border-collapse border border-gray-300 dark:border-gray-600">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="border border-gray-300 dark:border-gray-600 p-2 text-left text-gray-900 dark:text-white">ID</th>
                        <th class="border border-gray-300 dark:border-gray-600 p-2 text-left text-gray-900 dark:text-white">Usuário</th>
                        <th class="border border-gray-300 dark:border-gray-600 p-2 text-left text-gray-900 dark:text-white">Evento</th>
                        <th class="border border-gray-300 dark:border-gray-600 p-2 text-left text-gray-900 dark:text-white">Tabela</th>
                        <th class="border border-gray-300 dark:border-gray-600 p-2 text-left text-gray-900 dark:text-white">ID Auditado</th>
                        <th class="border border-gray-300 dark:border-gray-600 p-2 text-left text-gray-900 dark:text-white">Data e Hora</th>
                        <th class="border border-gray-300 dark:border-gray-600 p-2 text-left text-gray-900 dark:text-white">Detalhes</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="audit in audits.data" :key="audit.id"
                        class="hover:bg-gray-50 dark:hover:bg-gray-800 bg-white dark:bg-gray-900">
                        <td class="border border-gray-300 dark:border-gray-600 p-2 text-gray-900 dark:text-white">{{ audit.id }}</td>
                        <td class="border border-gray-300 dark:border-gray-600 p-2 text-gray-900 dark:text-white">{{ audit.user?.name ?? 'Sistema' }}</td>
                        <td class="border border-gray-300 dark:border-gray-600 p-2">
                            <span :class="eventoClasse(audit.event)"
                                class="px-2 py-1 rounded-full text-xs font-medium">
                                {{ eventoLabel(audit.event) }}
                            </span>
                        </td>
                        <td class="border border-gray-300 dark:border-gray-600 p-2 text-gray-900 dark:text-white">{{ tabelaLabel(audit.auditable_type) }}</td>
                        <td class="border border-gray-300 dark:border-gray-600 p-2 text-gray-900 dark:text-white">{{ audit.auditable_id }}</td>
                        <td class="border border-gray-300 dark:border-gray-600 p-2 text-gray-900 dark:text-white">{{ new Date(audit.created_at).toLocaleString('pt-BR') }}</td>
                        <td class="border border-gray-300 dark:border-gray-600 p-2">
                            <Link :href="route('auditoria.show', { audit: audit.id })"
                                class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 text-sm">
                                Detalhes
                            </Link>
                        </td>
                    </tr>
                    <tr v-if="audits.data.length === 0">
                        <td colspan="7" class="border border-gray-300 dark:border-gray-600 p-4 text-center text-gray-500 dark:text-gray-400">
                            Nenhum registro de auditoria encontrado.
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Paginação -->
            <div class="flex justify-between items-center mt-4">
                <span class="text-sm text-gray-600 dark:text-gray-400">
                    Página {{ audits.current_page }} de {{ audits.last_page }}
                </span>
                <div class="flex gap-2">
                    <Link v-if="audits.prev_page_url" :href="audits.prev_page_url"
                        class="px-3 py-1 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-300 dark:hover:bg-gray-600 text-sm">
                        Anterior
                    </Link>
                    <Link v-if="audits.next_page_url" :href="audits.next_page_url"
                        class="px-3 py-1 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-300 dark:hover:bg-gray-600 text-sm">
                        Próximo
                    </Link>
                </div>
            </div>

        </div>
    </AppLayout>
</template>
