<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'

defineProps<{
    pessoas: Array<{ id: number; nome: string }>
}>()

const form = useForm({
    pessoa_id: '',
    cep: '',
    endereco: '',
    numero: '',
    complemento: '',
    bairro: '',
    cidade: '',
    tipo: '',
    area_terreno: '',
    area_edificacao: '',
})

function mascaraCep(e: Event) {
    const input = e.target as HTMLInputElement
    let v = input.value.replace(/\D/g, '').slice(0, 8)
    v = v.replace(/(\d{5})(\d)/, '$1-$2')
    form.cep = v
    input.value = v
    if (v.replace(/\D/g, '').length === 8) {
        buscarCep(v.replace(/\D/g, ''))
    }
}

async function buscarCep(cep: string) {
    try {
        const response = await fetch(`https://viacep.com.br/ws/${cep}/json/`)
        const data = await response.json()
        if (!data.erro) {
            form.endereco = data.logradouro
            form.bairro = data.bairro
            form.cidade = data.localidade
        } else {
            alert('CEP não encontrado!')
        }
    } catch {
        alert('Erro ao buscar CEP!')
    }
}

function apenasNumeros(e: Event) {
    const input = e.target as HTMLInputElement
    input.value = input.value.replace(/\D/g, '')
    form.numero = input.value
}

function salvar() {
    form.post(route('imoveis.store'))
}
</script>

<template>
    <Head title="Novo Imóvel" />
    <AppLayout :breadcrumbs="[{ title: 'Imóveis', href: '/imoveis' }, { title: 'Novo Imóvel', href: '/imoveis/create' }]">
        <div class="p-6 max-w-2xl">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Novo Imóvel</h1>
                <Link :href="route('imoveis.index')" class="text-gray-500 hover:underline dark:text-gray-300">
                    Voltar
                </Link>
            </div>

            <div class="flex flex-col gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Contribuinte *</label>
                    <select v-model="form.pessoa_id"
                        class="w-full border rounded px-3 py-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600">
                        <option value="">Selecione...</option>
                        <option v-for="pessoa in pessoas" :key="pessoa.id" :value="pessoa.id">
                            {{ pessoa.nome }}
                        </option>
                    </select>
                    <span v-if="form.errors.pessoa_id" class="text-red-500 text-sm">{{ form.errors.pessoa_id }}</span>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Tipo *</label>
                    <select v-model="form.tipo"
                        class="w-full border rounded px-3 py-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600">
                        <option value="">Selecione...</option>
                        <option value="casa">Casa</option>
                        <option value="apartamento">Apartamento</option>
                        <option value="terreno">Terreno</option>
                    </select>
                    <span v-if="form.errors.tipo" class="text-red-500 text-sm">{{ form.errors.tipo }}</span>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">CEP</label>
                    <input :value="form.cep" @input="mascaraCep" type="text" placeholder="00000-000"
                        maxlength="9"
                        class="w-full border rounded px-3 py-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600" />
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Logradouro *</label>
                    <input v-model="form.endereco" type="text"
                        class="w-full border rounded px-3 py-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600" />
                    <span v-if="form.errors.endereco" class="text-red-500 text-sm">{{ form.errors.endereco }}</span>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Número *</label>
                        <input :value="form.numero" @input="apenasNumeros" type="text" placeholder="Ex: 123"
                            maxlength="10"
                            class="w-full border rounded px-3 py-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600" />
                        <span v-if="form.errors.numero" class="text-red-500 text-sm">{{ form.errors.numero }}</span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Complemento</label>
                        <input v-model="form.complemento" type="text" placeholder="Ex: Apto 404"
                            class="w-full border rounded px-3 py-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600" />
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Bairro *</label>
                    <input v-model="form.bairro" type="text"
                        class="w-full border rounded px-3 py-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600" />
                    <span v-if="form.errors.bairro" class="text-red-500 text-sm">{{ form.errors.bairro }}</span>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Cidade</label>
                    <input v-model="form.cidade" type="text"
                        class="w-full border rounded px-3 py-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600" />
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Área do Terreno (m²)</label>
                        <input v-model="form.area_terreno" type="number" step="0.01" min="0" placeholder="0.00"
                            class="w-full border rounded px-3 py-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600" />
                        <span v-if="form.errors.area_terreno" class="text-red-500 text-sm">{{ form.errors.area_terreno }}</span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Área da Edificação (m²)</label>
                        <input v-model="form.area_edificacao" type="number" step="0.01" min="0" placeholder="0.00"
                            class="w-full border rounded px-3 py-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600" />
                        <span v-if="form.errors.area_edificacao" class="text-red-500 text-sm">{{ form.errors.area_edificacao }}</span>
                    </div>
                </div>

                <button @click="salvar"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 w-full">
                    Salvar
                </button>
            </div>
        </div>
    </AppLayout>
</template>
