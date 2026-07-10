<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'

const props = defineProps<{
    usuario: {
        id: number
        name: string
        cpf: string
        email: string
        perfil: string
        ativo: string
    }
}>()

const form = useForm({
    name: props.usuario.name,
    perfil: props.usuario.perfil,
    ativo: props.usuario.ativo,
    password: '',
    password_confirmation: '',
})

function atualizar() {
    form.put(route('usuarios.update', props.usuario.id))
}
</script>

<template>
    <Head title="Editar Usuário" />
    <AppLayout :breadcrumbs="[{ title: 'Usuários', href: '/usuarios' }, { title: 'Editar', href: '#' }]">
        <div class="p-6 max-w-lg">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Editar Usuário</h1>
                <Link :href="route('usuarios.index')" class="text-gray-500 hover:underline dark:text-gray-300">
                    Voltar
                </Link>
            </div>

            <div class="flex flex-col gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Nome *</label>
                    <input v-model="form.name" type="text"
                        class="w-full border rounded px-3 py-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600"
                        :class="{ 'border-red-500': form.errors.name }" />
                    <span v-if="form.errors.name" class="text-red-500 text-sm">{{ form.errors.name }}</span>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">CPF</label>
                    <input :value="usuario.cpf" type="text" disabled
                        class="w-full border rounded px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 border-gray-300 dark:border-gray-600 cursor-not-allowed" />
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">E-mail</label>
                    <input :value="usuario.email" type="text" disabled
                        class="w-full border rounded px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 border-gray-300 dark:border-gray-600 cursor-not-allowed" />
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Perfil *</label>
                    <select v-model="form.perfil"
                        class="w-full border rounded px-3 py-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600"
                        :class="{ 'border-red-500': form.errors.perfil }">
                        <option value="T">Administrador TI</option>
                        <option value="S">Administrador Sistema</option>
                        <option value="A">Atendente</option>
                    </select>
                    <span v-if="form.errors.perfil" class="text-red-500 text-sm">{{ form.errors.perfil }}</span>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Ativo *</label>
                    <select v-model="form.ativo"
                        class="w-full border rounded px-3 py-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600">
                        <option value="S">Ativo</option>
                        <option value="N">Inativo</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Nova Senha (deixe em branco para não alterar)</label>
                    <input v-model="form.password" type="password"
                        class="w-full border rounded px-3 py-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600"
                        :class="{ 'border-red-500': form.errors.password }" />
                    <span v-if="form.errors.password" class="text-red-500 text-sm">{{ form.errors.password }}</span>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Confirmar Nova Senha</label>
                    <input v-model="form.password_confirmation" type="password"
                        class="w-full border rounded px-3 py-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600" />
                </div>

                <button @click="atualizar" :disabled="form.processing"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 w-full disabled:opacity-50">
                    {{ form.processing ? 'Salvando...' : 'Atualizar' }}
                </button>
            </div>
        </div>
    </AppLayout>
</template>
