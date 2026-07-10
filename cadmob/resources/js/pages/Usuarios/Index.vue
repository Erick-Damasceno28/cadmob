<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, Link, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

defineProps<{
    usuarios: {
        data: Array<{
            id: number
            name: string
            email: string
            cpf: string
            perfil: string
            ativo: string
        }>
        current_page: number
        last_page: number
        next_page_url: string | null
        prev_page_url: string | null
    }
}>()

const page = usePage()
const flash = computed(() => page.props.flash as { success?: string })

function perfilLabel(perfil: string) {
    const perfis: Record<string, string> = {
        T: 'Administrador TI',
        S: 'Administrador Sistema',
        A: 'Atendente'
    }
    return perfis[perfil] ?? perfil
}
</script>

<template>
    <Head title="Usuários" />
    <AppLayout :breadcrumbs="[{ title: 'Usuários', href: '/usuarios' }]">
        <div class="p-6">

            <div v-if="flash.success"
                class="mb-4 p-3 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded border border-green-300 dark:border-green-700">
                {{ flash.success }}
            </div>

            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Usuários</h1>
                <Link :href="route('usuarios.create')"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Novo Usuário
                </Link>
            </div>

            <table class="w-full border-collapse border border-gray-300 dark:border-gray-600">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="border border-gray-300 dark:border-gray-600 p-2 text-left text-gray-900 dark:text-white">ID</th>
                        <th class="border border-gray-300 dark:border-gray-600 p-2 text-left text-gray-900 dark:text-white">Nome</th>
                        <th class="border border-gray-300 dark:border-gray-600 p-2 text-left text-gray-900 dark:text-white">E-mail</th>
                        <th class="border border-gray-300 dark:border-gray-600 p-2 text-left text-gray-900 dark:text-white">Perfil</th>
                        <th class="border border-gray-300 dark:border-gray-600 p-2 text-left text-gray-900 dark:text-white">Ativo</th>
                        <th class="border border-gray-300 dark:border-gray-600 p-2 text-left text-gray-900 dark:text-white">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="usuario in usuarios.data" :key="usuario.id"
                        class="hover:bg-gray-50 dark:hover:bg-gray-800 bg-white dark:bg-gray-900">
                        <td class="border border-gray-300 dark:border-gray-600 p-2 text-gray-900 dark:text-white">{{ usuario.id }}</td>
                        <td class="border border-gray-300 dark:border-gray-600 p-2 text-gray-900 dark:text-white">{{ usuario.name }}</td>
                        <td class="border border-gray-300 dark:border-gray-600 p-2 text-gray-900 dark:text-white">{{ usuario.email }}</td>
                        <td class="border border-gray-300 dark:border-gray-600 p-2 text-gray-900 dark:text-white">{{ perfilLabel(usuario.perfil) }}</td>
                        <td class="border border-gray-300 dark:border-gray-600 p-2">
                            <span :class="usuario.ativo === 'S'
                                ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
                                : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'"
                                class="px-2 py-1 rounded-full text-xs font-medium">
                                {{ usuario.ativo === 'S' ? '● Ativo' : '● Inativo' }}
                            </span>
                        </td>
                        <td class="border border-gray-300 dark:border-gray-600 p-2">
                            <Link :href="route('usuarios.edit', { usuario: usuario.id })"
                                class="bg-yellow-400 text-white px-3 py-1 rounded hover:bg-yellow-500 text-sm">
                                Visualizar
                            </Link>
                        </td>
                    </tr>
                    <tr v-if="usuarios.data.length === 0">
                        <td colspan="6" class="border border-gray-300 dark:border-gray-600 p-4 text-center text-gray-500 dark:text-gray-400">
                            Nenhum usuário cadastrado.
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="flex justify-between items-center mt-4">
                <span class="text-sm text-gray-600 dark:text-gray-400">
                    Página {{ usuarios.current_page }} de {{ usuarios.last_page }}
                </span>
                <div class="flex gap-2">
                    <Link v-if="usuarios.prev_page_url" :href="usuarios.prev_page_url"
                        class="px-3 py-1 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-300 dark:hover:bg-gray-600 text-sm">
                        Anterior
                    </Link>
                    <Link v-if="usuarios.next_page_url" :href="usuarios.next_page_url"
                        class="px-3 py-1 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-300 dark:hover:bg-gray-600 text-sm">
                        Próximo
                    </Link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
