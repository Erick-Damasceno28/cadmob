<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'

const form = useForm({
    name: '',
    cpf: '',
    email: '',
    password: '',
    password_confirmation: '',
    perfil: 'A'
})

function mascaraCpf(e: Event) {
    const input = e.target as HTMLInputElement
    let v = input.value.replace(/\D/g, '').slice(0, 11)
    v = v.replace(/(\d{3})(\d)/, '$1.$2')
    v = v.replace(/(\d{3})(\d)/, '$1.$2')
    v = v.replace(/(\d{3})(\d{1,2})$/, '$1-$2')
    form.cpf = v
    input.value = v
}

function salvar() {
    form.post(route('usuarios.store'))
}
</script>

<template>
    <Head title="Novo Usuário" />
    <AppLayout :breadcrumbs="[{ title: 'Usuários', href: '/usuarios' }, { title: 'Novo Usuário', href: '/usuarios/create' }]">
        <div class="p-6 max-w-lg">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Novo Usuário</h1>
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
                    <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">CPF *</label>
                    <input :value="form.cpf" @input="mascaraCpf" type="text" placeholder="000.000.000-00"
                        maxlength="14"
                        class="w-full border rounded px-3 py-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600"
                        :class="{ 'border-red-500': form.errors.cpf }" />
                    <span v-if="form.errors.cpf" class="text-red-500 text-sm">{{ form.errors.cpf }}</span>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">E-mail *</label>
                    <input v-model="form.email" type="email"
                        class="w-full border rounded px-3 py-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600"
                        :class="{ 'border-red-500': form.errors.email }" />
                    <span v-if="form.errors.email" class="text-red-500 text-sm">{{ form.errors.email }}</span>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Senha *</label>
                    <input v-model="form.password" type="password"
                        class="w-full border rounded px-3 py-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600"
                        :class="{ 'border-red-500': form.errors.password }" />
                    <span v-if="form.errors.password" class="text-red-500 text-sm">{{ form.errors.password }}</span>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Confirmar Senha *</label>
                    <input v-model="form.password_confirmation" type="password"
                        class="w-full border rounded px-3 py-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600" />
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

                <button @click="salvar" :disabled="form.processing"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 w-full disabled:opacity-50">
                    {{ form.processing ? 'Salvando...' : 'Salvar' }}
                </button>
            </div>
        </div>
    </AppLayout>
</template>
