<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="CadImob" />
    <div class="min-h-screen flex flex-col items-center justify-center bg-gray-50">

        <!-- Logo e título -->
        <div class="text-center mb-8">
            <div class="flex items-center justify-center gap-4 mb-4">
                <img src="/brasao.jpg" alt="Brasão de São Leopoldo" class="w-20 h-20 object-contain" />
                <div class="text-left">
                    <h1 class="text-4xl font-bold text-blue-700">CadImob</h1>
                    <p class="text-gray-500 text-sm">Prefeitura Municipal de São Leopoldo</p>
                    <p class="text-gray-400 text-xs">Estado do Rio Grande do Sul</p>
                </div>
            </div>
            <p class="text-gray-500 text-base mt-2">Sistema de Cadastro Imobiliário</p>
        </div>

        <!-- Card de acesso -->
        <div class="bg-white rounded-xl shadow-md p-8 w-full max-w-sm border border-gray-100">
            <div v-if="$page.props.auth.user">
                <p class="text-center text-gray-600 mb-4">
                    Bem-vindo, <strong>{{ ($page.props.auth.user as any).name }}</strong>!
                </p>
                <Link
                    :href="route('dashboard')"
                    class="block w-full text-center bg-blue-700 text-white py-2.5 rounded-lg hover:bg-blue-800 transition font-medium">
                    Ir para o Dashboard
                </Link>
            </div>
            <div v-else>
                <p class="text-center text-gray-500 mb-6 text-sm">
                    Faça login para acessar o sistema
                </p>
                <Link
                    :href="route('login')"
                    class="block w-full text-center bg-blue-700 text-white py-2.5 rounded-lg hover:bg-blue-800 transition font-medium">
                    Entrar
                </Link>
            </div>
        </div>

        <p class="text-gray-400 text-xs mt-8">
            © {{ new Date().getFullYear() }} Prefeitura Municipal de São Leopoldo
        </p>
    </div>
</template>
