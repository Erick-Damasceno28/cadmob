<script setup lang="ts">
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <AuthBase title="Acesse sua conta" description="Informe seu e-mail e senha para entrar no sistema">
        <Head title="Login - CadImob" />

        <div v-if="status" class="mb-4 text-center text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <div class="flex flex-col gap-4">
            <div class="flex flex-col gap-1">
                <label class="text-sm font-medium text-gray-700">E-mail</label>
                <input
                    v-model="form.email"
                    type="email"
                    placeholder="seu@email.com"
                    required
                    autofocus
                    autocomplete="email"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-900 bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
                <span v-if="form.errors.email" class="text-red-500 text-xs">{{ form.errors.email }}</span>
            </div>

            <div class="flex flex-col gap-1">
                <div class="flex justify-between items-center">
                    <label class="text-sm font-medium text-gray-700">Senha</label>
                    <a v-if="canResetPassword" :href="route('password.request')" class="text-xs text-blue-600 hover:underline">
                        Esqueceu a senha?
                    </a>
                </div>
                <input
                    v-model="form.password"
                    type="password"
                    placeholder="Sua senha"
                    required
                    autocomplete="current-password"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-900 bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
                <span v-if="form.errors.password" class="text-red-500 text-xs">{{ form.errors.password }}</span>
            </div>

            <div class="flex items-center gap-2">
                <input
                    id="remember"
                    v-model="form.remember"
                    type="checkbox"
                    class="w-4 h-4 text-blue-600 border-gray-300 rounded"
                />
                <label for="remember" class="text-sm text-gray-600">Lembrar de mim</label>
            </div>

            <button
                @click="submit"
                :disabled="form.processing"
                class="w-full bg-blue-700 text-white py-2.5 rounded-lg hover:bg-blue-800 transition font-medium disabled:opacity-50 flex items-center justify-center gap-2 mt-2">
                <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                {{ form.processing ? 'Entrando...' : 'Entrar' }}
            </button>
        </div>
    </AuthBase>
</template>
