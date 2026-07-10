<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'

const props = defineProps<{
    pessoa: {
        id: number
        nome: string
        cpf: string
        email: string
        telefone: string
        data_nascimento: string
        sexo: string
    }
}>()

const form = useForm({
    nome: props.pessoa.nome,
    cpf: props.pessoa.cpf,
    email: props.pessoa.email,
    telefone: props.pessoa.telefone,
    data_nascimento: props.pessoa.data_nascimento,
    sexo: props.pessoa.sexo
})

function mascaraTelefone(e: Event) {
    const input = e.target as HTMLInputElement
    let v = input.value.replace(/\D/g, '').slice(0, 11)
    if (v.length <= 10) {
        v = v.replace(/(\d{2})(\d)/, '($1) $2')
        v = v.replace(/(\d{4})(\d)/, '$1-$2')
    } else {
        v = v.replace(/(\d{2})(\d)/, '($1) $2')
        v = v.replace(/(\d{5})(\d)/, '$1-$2')
    }
    form.telefone = v
    input.value = v
}

function atualizar() {
    form.put(route('pessoas.update', props.pessoa.id))
}
</script>

<template>
    <Head title="Editar Pessoa" />
    <AppLayout :breadcrumbs="[{ title: 'Pessoas', href: '/pessoas' }, { title: 'Editar', href: '#' }]">
        <div class="p-6 max-w-lg">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Editar Pessoa</h1>
                <Link :href="route('pessoas.index')" class="text-gray-500 hover:underline dark:text-gray-300">
                    Voltar
                </Link>
            </div>

            <div class="flex flex-col gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Nome *</label>
                    <input v-model="form.nome" type="text"
                        class="w-full border rounded px-3 py-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600" />
                    <span v-if="form.errors.nome" class="text-red-500 text-sm">{{ form.errors.nome }}</span>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">CPF</label>
                    <input v-model="form.cpf" type="text" disabled
                        class="w-full border rounded px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 border-gray-300 dark:border-gray-600 cursor-not-allowed" />
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Data de Nascimento *</label>
                    <input v-model="form.data_nascimento" type="date"
                        class="w-full border rounded px-3 py-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600" />
                    <span v-if="form.errors.data_nascimento" class="text-red-500 text-sm">{{ form.errors.data_nascimento }}</span>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Sexo *</label>
                    <div class="flex gap-4 mt-1">
                        <label class="flex items-center gap-2 text-gray-700 dark:text-gray-300 cursor-pointer">
                            <input type="radio" v-model="form.sexo" value="M"
                                class="w-4 h-4 accent-blue-600" />
                            Masculino
                        </label>
                        <label class="flex items-center gap-2 text-gray-700 dark:text-gray-300 cursor-pointer">
                            <input type="radio" v-model="form.sexo" value="F"
                                class="w-4 h-4 accent-blue-600" />
                            Feminino
                        </label>
                    </div>
                    <span v-if="form.errors.sexo" class="text-red-500 text-sm">{{ form.errors.sexo }}</span>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Email</label>
                    <input v-model="form.email" type="email"
                        class="w-full border rounded px-3 py-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600" />
                    <span v-if="form.errors.email" class="text-red-500 text-sm">{{ form.errors.email }}</span>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Telefone</label>
                    <input :value="form.telefone" @input="mascaraTelefone" type="text" placeholder="(00) 00000-0000"
                        maxlength="15"
                        class="w-full border rounded px-3 py-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600" />
                </div>

                <button @click="atualizar"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 w-full">
                    Atualizar
                </button>
            </div>
        </div>
    </AppLayout>
</template>
