<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, Link, router, usePage } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

const props = defineProps<{
    imoveis: {
        data: Array<{
            id: number
            endereco: string
            numero: string
            bairro: string
            cidade: string
            tipo: string
            situacao: string
            pessoa: { nome: string }
        }>
        current_page: number
        last_page: number
        next_page_url: string | null
        prev_page_url: string | null
    }
    filtros: {
        endereco?: string
        bairro?: string
        tipo?: string
        situacao?: string
    }
}>()

const page = usePage()
const flash = computed(() => page.props.flash as { success?: string })

const filtro = ref({
    endereco: props.filtros.endereco ?? '',
    bairro: props.filtros.bairro ?? '',
    tipo: props.filtros.tipo ?? '',
    situacao: props.filtros.situacao ?? '',
})

function buscar() {
    router.get(route('imoveis.index'), filtro.value, {
        preserveState: true,
        replace: true,
    })
}

function limpar() {
    filtro.value = { endereco: '', bairro: '', tipo: '', situacao: '' }
    buscar()
}

function deletar(id: number) {
    if (confirm('Deseja excluir este imóvel?')) {
        router.delete(`/imoveis/${id}`)
    }
}

function tipoLabel(tipo: string) {
    const tipos: Record<string, string> = {
        casa: 'Casa',
        apartamento: 'Apartamento',
        terreno: 'Terreno'
    }
    return tipos[tipo] ?? tipo
}
</script>

<template>
    <Head title="Imóveis" />
    <AppLayout :breadcrumbs="[{ title: 'Imóveis', href: '/imoveis' }]">
        <div class="p-6">

            <!-- Mensagem de sucesso -->
            <div v-if="flash.success"
                class="mb-4 p-3 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded border border-green-300 dark:border-green-700">
                {{ flash.success }}
            </div>

            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Imóveis</h1>
                <div class="flex gap-2">
                    <a :href="route('relatorios.sintetico')" target="_blank"
                        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 text-sm">
                        📄 Relatório PDF
                    </a>
                    <Link :href="route('imoveis.create')"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Novo Imóvel
                    </Link>
                </div>
            </div>

            <!-- Filtros -->
            <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded mb-4 border border-gray-200 dark:border-gray-700">
                <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Filtros</h2>
                <div class="grid grid-cols-2 gap-3 md:grid-cols-4">
                    <div>
                        <label class="block text-xs font-medium mb-1 text-gray-600 dark:text-gray-400">Logradouro</label>
                        <input v-model="filtro.endereco" type="text" placeholder="Buscar por logradouro..."
                            class="w-full border rounded px-2 py-1 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600" />
                    </div>
                    <div>
                        <label class="block text-xs font-medium mb-1 text-gray-600 dark:text-gray-400">Bairro</label>
                        <input v-model="filtro.bairro" type="text" placeholder="Buscar por bairro..."
                            class="w-full border rounded px-2 py-1 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600" />
                    </div>
                    <div>
                        <label class="block text-xs font-medium mb-1 text-gray-600 dark:text-gray-400">Tipo</label>
                        <select v-model="filtro.tipo"
                            class="w-full border rounded px-2 py-1 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600">
                            <option value="">Todos</option>
                            <option value="casa">Casa</option>
                            <option value="apartamento">Apartamento</option>
                            <option value="terreno">Terreno</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium mb-1 text-gray-600 dark:text-gray-400">Situação</label>
                        <select v-model="filtro.situacao"
                            class="w-full border rounded px-2 py-1 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600">
                            <option value="">Todas</option>
                            <option value="ativo">Ativo</option>
                            <option value="inativo">Inativo</option>
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
                        <th class="border border-gray-300 dark:border-gray-600 p-2 text-left text-gray-900 dark:text-white">Inscrição</th>
                        <th class="border border-gray-300 dark:border-gray-600 p-2 text-left text-gray-900 dark:text-white">Contribuinte</th>
                        <th class="border border-gray-300 dark:border-gray-600 p-2 text-left text-gray-900 dark:text-white">Tipo</th>
                        <th class="border border-gray-300 dark:border-gray-600 p-2 text-left text-gray-900 dark:text-white">Logradouro</th>
                        <th class="border border-gray-300 dark:border-gray-600 p-2 text-left text-gray-900 dark:text-white">Número</th>
                        <th class="border border-gray-300 dark:border-gray-600 p-2 text-left text-gray-900 dark:text-white">Bairro</th>
                        <th class="border border-gray-300 dark:border-gray-600 p-2 text-left text-gray-900 dark:text-white">Situação</th>
                        <th class="border border-gray-300 dark:border-gray-600 p-2 text-left text-gray-900 dark:text-white">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="imovel in imoveis.data" :key="imovel.id"
                        class="hover:bg-gray-50 dark:hover:bg-gray-800 bg-white dark:bg-gray-900">
                        <td class="border border-gray-300 dark:border-gray-600 p-2 text-gray-900 dark:text-white">{{ imovel.id }}</td>
                        <td class="border border-gray-300 dark:border-gray-600 p-2 text-gray-900 dark:text-white">{{ imovel.pessoa?.nome }}</td>
                        <td class="border border-gray-300 dark:border-gray-600 p-2 text-gray-900 dark:text-white">{{ tipoLabel(imovel.tipo) }}</td>
                        <td class="border border-gray-300 dark:border-gray-600 p-2 text-gray-900 dark:text-white">{{ imovel.endereco }}</td>
                        <td class="border border-gray-300 dark:border-gray-600 p-2 text-gray-900 dark:text-white">{{ imovel.numero }}</td>
                        <td class="border border-gray-300 dark:border-gray-600 p-2 text-gray-900 dark:text-white">{{ imovel.bairro }}</td>
                        <td class="border border-gray-300 dark:border-gray-600 p-2">
                            <span :class="imovel.situacao === 'ativo'
                                ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
                                : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'"
                                class="px-2 py-1 rounded-full text-xs font-medium">
                                {{ imovel.situacao === 'ativo' ? '● Ativo' : '● Inativo' }}
                            </span>
                        </td>
                        <td class="border border-gray-300 dark:border-gray-600 p-2">
                            <div class="flex gap-2">
                                <a :href="`/relatorios/imovel/${imovel.id}`" target="_blank"
                                    class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 text-sm">
                                    PDF
                                </a>
                                <Link :href="`/imoveis/${imovel.id}/edit`"
                                    class="bg-yellow-400 text-white px-3 py-1 rounded hover:bg-yellow-500 text-sm">
                                    Editar
                                </Link>
                                <button @click="deletar(imovel.id)"
                                    class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-sm">
                                    Excluir
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="imoveis.data.length === 0">
                        <td colspan="8" class="border border-gray-300 dark:border-gray-600 p-4 text-center text-gray-500 dark:text-gray-400">
                            Nenhum imóvel cadastrado.
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Paginação -->
            <div class="flex justify-between items-center mt-4">
                <span class="text-sm text-gray-600 dark:text-gray-400">
                    Página {{ imoveis.current_page }} de {{ imoveis.last_page }}
                </span>
                <div class="flex gap-2">
                    <Link v-if="imoveis.prev_page_url" :href="imoveis.prev_page_url"
                        class="px-3 py-1 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-300 dark:hover:bg-gray-600 text-sm">
                        Anterior
                    </Link>
                    <Link v-if="imoveis.next_page_url" :href="imoveis.next_page_url"
                        class="px-3 py-1 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-300 dark:hover:bg-gray-600 text-sm">
                        Próximo
                    </Link>
                </div>
            </div>

        </div>
    </AppLayout>
</template>
