<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, Link, useForm, router } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps<{
    imovel: {
        id: number
        pessoa_id: number
        cep: string
        endereco: string
        numero: string
        complemento: string
        bairro: string
        cidade: string
        tipo: string
        area_terreno: number
        area_edificacao: number
        situacao: string
        documentos: Array<{
            id: number
            nome_original: string
            tamanho: number
        }>
        averbacoes: Array<{
            id: number
            evento: string
            medida: number | null
            descricao: string | null
            data: string
        }>
    }
    pessoas: Array<{ id: number; nome: string }>
}>()

const form = useForm({
    pessoa_id: props.imovel.pessoa_id,
    cep: props.imovel.cep,
    endereco: props.imovel.endereco,
    numero: props.imovel.numero,
    complemento: props.imovel.complemento,
    bairro: props.imovel.bairro,
    cidade: props.imovel.cidade,
    tipo: props.imovel.tipo,
    area_terreno: props.imovel.area_terreno,
    area_edificacao: props.imovel.area_edificacao,
})

const docForm = useForm({
    documento: null as File | null
})

const avForm = useForm({
    evento: '',
    medida: '',
    descricao: '',
})

const eventosMedida = ['aumento_area', 'reducao_area']
const mostrarMedida = computed(() => eventosMedida.includes(avForm.evento))

function eventoLabel(evento: string) {
    const eventos: Record<string, string> = {
        aumento_area: 'Aumento Área Construída',
        reducao_area: 'Redução Área Construída',
        observacao: 'Observação',
        cancelamento: 'Cancelamento',
        reativacao: 'Reativação',
    }
    return eventos[evento] ?? evento
}

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

function atualizar() {
    const id = props.imovel?.id ?? null
    if (!id) {
        alert('Erro: ID do imóvel não encontrado!')
        return
    }
    form.put(`/imoveis/${id}`)
}

function selecionarArquivo(e: Event) {
    const input = e.target as HTMLInputElement
    if (input.files && input.files[0]) {
        docForm.documento = input.files[0]
    }
}

function enviarDocumento() {
    docForm.post(route('documentos.store', props.imovel.id), {
        forceFormData: true,
    })
}

function excluirDocumento(id: number) {
    if (confirm('Deseja excluir este documento?')) {
        router.delete(route('documentos.destroy', id))
    }
}

function formatarTamanho(bytes: number) {
    if (bytes < 1024) return bytes + ' B'
    if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB'
    return (bytes / (1024 * 1024)).toFixed(1) + ' MB'
}

function registrarAverbacao() {
    avForm.post(route('averbacoes.store', props.imovel.id))
}
</script>

<template>
    <Head title="Editar Imóvel" />
    <AppLayout :breadcrumbs="[{ title: 'Imóveis', href: '/imoveis' }, { title: 'Editar', href: '#' }]">
        <div class="p-6 max-w-2xl">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Editar Imóvel</h1>
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
                    <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Situação</label>
                    <input :value="imovel.situacao === 'ativo' ? 'Ativo' : 'Inativo'" type="text" disabled
                        class="w-full border rounded px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 border-gray-300 dark:border-gray-600 cursor-not-allowed capitalize" />
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
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Área da Edificação (m²)</label>
                        <input v-model="form.area_edificacao" type="number" step="0.01" min="0" placeholder="0.00"
                            class="w-full border rounded px-3 py-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600" />
                    </div>
                </div>

                <button @click="atualizar"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 w-full">
                    Atualizar
                </button>
            </div>

            <!-- Seção de Averbações -->
            <div class="mt-8">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Averbações</h2>

                <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded border border-gray-200 dark:border-gray-700 mb-4">
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Nova Averbação</h3>

                    <div class="flex flex-col gap-3">
                        <div>
                            <label class="block text-xs font-medium mb-1 text-gray-600 dark:text-gray-400">Evento *</label>
                            <select v-model="avForm.evento"
                                class="w-full border rounded px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600 text-sm">
                                <option value="">Selecione...</option>
                                <option value="aumento_area">Aumento Área Construída</option>
                                <option value="reducao_area">Redução Área Construída</option>
                                <option value="observacao">Observação</option>
                                <option value="cancelamento">Cancelamento</option>
                                <option value="reativacao">Reativação</option>
                            </select>
                            <span v-if="avForm.errors.evento" class="text-red-500 text-sm">{{ avForm.errors.evento }}</span>
                        </div>

                        <div v-if="mostrarMedida">
                            <label class="block text-xs font-medium mb-1 text-gray-600 dark:text-gray-400">Medida (m²) *</label>
                            <input v-model="avForm.medida" type="number" step="0.01" min="0.01" placeholder="0.00"
                                class="w-full border rounded px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600 text-sm" />
                            <span v-if="avForm.errors.medida" class="text-red-500 text-sm">{{ avForm.errors.medida }}</span>
                        </div>

                        <div>
                            <label class="block text-xs font-medium mb-1 text-gray-600 dark:text-gray-400">Descrição</label>
                            <textarea v-model="avForm.descricao" rows="3" placeholder="Descrição do evento..."
                                class="w-full border rounded px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600 text-sm"></textarea>
                        </div>

                        <button @click="registrarAverbacao" :disabled="avForm.processing"
                            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm disabled:opacity-50">
                            {{ avForm.processing ? 'Registrando...' : 'Registrar Averbação' }}
                        </button>
                    </div>
                </div>

                <!-- Lista de averbações -->
                <div v-if="imovel.averbacoes.length > 0" class="flex flex-col gap-2">
                    <table class="w-full border-collapse border border-gray-300 dark:border-gray-600">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th class="border border-gray-300 dark:border-gray-600 p-2 text-left text-gray-900 dark:text-white text-sm">Data</th>
                                <th class="border border-gray-300 dark:border-gray-600 p-2 text-left text-gray-900 dark:text-white text-sm">Evento</th>
                                <th class="border border-gray-300 dark:border-gray-600 p-2 text-left text-gray-900 dark:text-white text-sm">Medida</th>
                                <th class="border border-gray-300 dark:border-gray-600 p-2 text-left text-gray-900 dark:text-white text-sm">Descrição</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="av in imovel.averbacoes" :key="av.id"
                                class="bg-white dark:bg-gray-900">
                                <td class="border border-gray-300 dark:border-gray-600 p-2 text-gray-900 dark:text-white text-sm">{{ av.data }}</td>
                                <td class="border border-gray-300 dark:border-gray-600 p-2 text-gray-900 dark:text-white text-sm">{{ eventoLabel(av.evento) }}</td>
                                <td class="border border-gray-300 dark:border-gray-600 p-2 text-gray-900 dark:text-white text-sm">{{ av.medida ? av.medida + ' m²' : '-' }}</td>
                                <td class="border border-gray-300 dark:border-gray-600 p-2 text-gray-900 dark:text-white text-sm">{{ av.descricao ?? '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-else class="text-center text-gray-500 dark:text-gray-400 text-sm py-4">
                    Nenhuma averbação registrada.
                </div>
            </div>

            <!-- Seção de Documentos -->
            <div class="mt-8">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Documentos</h2>

                <div v-if="imovel.documentos.length < 5"
                    class="bg-gray-50 dark:bg-gray-800 p-4 rounded border border-gray-200 dark:border-gray-700 mb-4">
                    <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">
                        Anexar documento (JPG, JPEG, PNG, PDF — máx. 3MB)
                    </label>
                    <div class="flex gap-2">
                        <input type="file" @change="selecionarArquivo"
                            accept=".jpg,.jpeg,.png,.pdf"
                            class="w-full border rounded px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600 text-sm" />
                        <button @click="enviarDocumento" :disabled="!docForm.documento || docForm.processing"
                            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm whitespace-nowrap disabled:opacity-50">
                            {{ docForm.processing ? 'Enviando...' : 'Anexar' }}
                        </button>
                    </div>
                    <span v-if="docForm.errors.documento" class="text-red-500 text-sm">{{ docForm.errors.documento }}</span>
                </div>

                <div v-else class="bg-yellow-50 dark:bg-yellow-900 p-3 rounded border border-yellow-200 dark:border-yellow-700 mb-4">
                    <p class="text-yellow-800 dark:text-yellow-200 text-sm">
                        ⚠️ Limite de 5 documentos atingido.
                    </p>
                </div>

                <div v-if="imovel.documentos.length > 0" class="flex flex-col gap-2">
                    <div v-for="doc in imovel.documentos" :key="doc.id"
                        class="flex justify-between items-center p-3 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded">
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ doc.nome_original }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ formatarTamanho(doc.tamanho) }}</p>
                        </div>
                        <div class="flex gap-2">
                            <a :href="route('documentos.download', doc.id)"
                                class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 text-sm">
                                Baixar
                            </a>
                            <button @click="excluirDocumento(doc.id)"
                                class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-sm">
                                Excluir
                            </button>
                        </div>
                    </div>
                </div>

                <div v-else class="text-center text-gray-500 dark:text-gray-400 text-sm py-4">
                    Nenhum documento anexado.
                </div>
            </div>

        </div>
    </AppLayout>
</template>
