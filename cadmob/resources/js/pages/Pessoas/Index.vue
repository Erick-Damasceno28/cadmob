<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, Link, router, usePage } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

const props = defineProps<{
    pessoas: {
        data: Array<{
            id: number
            nome: string
            cpf: string
            email: string
            telefone: string
            data_nascimento: string
            sexo: string
        }>
        current_page: number
        last_page: number
        next_page_url: string | null
        prev_page_url: string | null
    }
    filtros: {
        nome?: string
        cpf?: string
        data_nascimento?: string
        sexo?: string
    }
}>()

const page = usePage()
const flash = computed(() => page.props.flash as { success?: string })

const filtro = ref({
    nome: props.filtros.nome ?? '',
    cpf: props.filtros.cpf ?? '',
    data_nascimento: props.filtros.data_nascimento ?? '',
    sexo: props.filtros.sexo ?? '',
})

function buscar() {
    router.get(route('pessoas.index'), filtro.value, {
        preserveState: true,
        replace: true,
    })
}

function limpar() {
    filtro.value = { nome: '', cpf: '', data_nascimento: '', sexo: '' }
    buscar()
}

function deletar(id: number) {
    if (confirm('Deseja excluir esta pessoa?')) {
        router.delete(route('pessoas.destroy', id))
    }
}

function sexoLabel(sexo: string) {
    return sexo === 'M' ? 'Masculino' : sexo === 'F' ? 'Feminino' : '-'
}
</script>

<template>
    <Head title="Pessoas" />
    <AppLayout :breadcrumbs="[{ title: 'Pessoas', href: '/pessoas' }]">
        <div class="p-6">

            <!-- Mensagem de sucesso -->
            <div v-if="flash.success"
                class="mb-4 p-3 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded border border-green-300 dark:border-green-700">
                {{ flash.success }}
            </div>

            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Pessoas</h1>
                <Link :href="route('pessoas.create')"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Nova Pessoa
                </Link>
            </div>

            <!-- Filtros -->
            <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded mb-4 border border-gray-200 dark:border-gray-700">
                <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Filtros</h2>
                <div class="grid grid-cols-2 gap-3 md:grid-cols-4">
                    <div>
                        <label class="block text-xs font-medium mb-1 text-gray-600 dark:text-gray-400">Nome</label>
                        <input v-model="filtro.nome" type="text" placeholder="Buscar por nome..."
                            class="w-full border rounded px-2 py-1 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600" />
                    </div>
                    <div>
                        <label class="block text-xs font-medium mb-1 text-gray-600 dark:text-gray-400">CPF</label>
                        <input v-model="filtro.cpf" type="text" placeholder="Buscar por CPF..."
                            class="w-full border rounded px-2 py-1 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600" />
                    </div>
                    <div>
                        <label class="block text-xs font-medium mb-1 text-gray-600 dark:text-gray-400">Data de Nascimento</label>
                        <input v-model="filtro.data_nascimento" type="date"
                            class="w-full border rounded px-2 py-1 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600" />
                    </div>
                    <div>
                        <label class="block text-xs font-medium mb-1 text-gray-600 dark:text-gray-400">Sexo</label>
                        <select v-model="filtro.sexo"
                            class="w-full border rounded px-2 py-1 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600">
                            <option value="">Todos</option>
                            <option value="M">Masculino</option>
                            <option value="F">Feminino</option>
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
                        <th class="border border-gray-300 dark:border-gray-600 p-2 text-left text-gray-900 dark:text-white">Nome</th>
                        <th class="border border-gray-300 dark:border-gray-600 p-2 text-left text-gray-900 dark:text-white">CPF</th>
                        <th class="border border-gray-300 dark:border-gray-600 p-2 text-left text-gray-900 dark:text-white">Data Nasc.</th>
                        <th class="border border-gray-300 dark:border-gray-600 p-2 text-left text-gray-900 dark:text-white">Sexo</th>
                        <th class="border border-gray-300 dark:border-gray-600 p-2 text-left text-gray-900 dark:text-white">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="pessoa in pessoas.data" :key="pessoa.id"
                        class="hover:bg-gray-50 dark:hover:bg-gray-800 bg-white dark:bg-gray-900">
                        <td class="border border-gray-300 dark:border-gray-600 p-2 text-gray-900 dark:text-white">{{ pessoa.id }}</td>
                        <td class="border border-gray-300 dark:border-gray-600 p-2 text-gray-900 dark:text-white">{{ pessoa.nome }}</td>
                        <td class="border border-gray-300 dark:border-gray-600 p-2 text-gray-900 dark:text-white">{{ pessoa.cpf }}</td>
                        <td class="border border-gray-300 dark:border-gray-600 p-2 text-gray-900 dark:text-white">{{ pessoa.data_nascimento }}</td>
                        <td class="border border-gray-300 dark:border-gray-600 p-2 text-gray-900 dark:text-white">{{ sexoLabel(pessoa.sexo) }}</td>
                        <td class="border border-gray-300 dark:border-gray-600 p-2">
                            <div class="flex gap-2">
                                <Link :href="route('pessoas.edit', pessoa.id)"
                                    class="bg-yellow-400 text-white px-3 py-1 rounded hover:bg-yellow-500 text-sm">
                                    Editar
                                </Link>
                                <button @click="deletar(pessoa.id)"
                                    class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-sm">
                                    Excluir
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="pessoas.data.length === 0">
                        <td colspan="6" class="border border-gray-300 dark:border-gray-600 p-4 text-center text-gray-500 dark:text-gray-400">
                            Nenhuma pessoa encontrada.
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Paginação -->
            <div class="flex justify-between items-center mt-4">
                <span class="text-sm text-gray-600 dark:text-gray-400">
                    Página {{ pessoas.current_page }} de {{ pessoas.last_page }}
                </span>
                <div class="flex gap-2">
                    <Link v-if="pessoas.prev_page_url" :href="pessoas.prev_page_url"
                        class="px-3 py-1 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-300 dark:hover:bg-gray-600 text-sm">
                        Anterior
                    </Link>
                    <Link v-if="pessoas.next_page_url" :href="pessoas.next_page_url"
                        class="px-3 py-1 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-300 dark:hover:bg-gray-600 text-sm">
                        Próximo
                    </Link>
                </div>
            </div>

        </div>
    </AppLayout>
</template>
