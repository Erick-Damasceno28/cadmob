<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div class="flex min-h-svh flex-col items-center justify-center gap-6 bg-gray-50 p-6 md:p-10">
        <div class="w-full max-w-sm">
            <div class="flex flex-col gap-8">
                <div class="flex flex-col items-center gap-4">
                    <Link :href="route('home')" class="flex flex-col items-center gap-3">
                        <img src="/brasao.jpg" alt="Brasão de São Leopoldo" class="w-16 h-16 object-contain" />
                        <div class="text-center">
                            <h2 class="text-2xl font-bold text-blue-700">CadImob</h2>
                            <p class="text-xs text-gray-400">Prefeitura Municipal de São Leopoldo</p>
                        </div>
                    </Link>
                    <div class="space-y-2 text-center">
                        <h1 class="text-xl font-medium text-gray-800">{{ title }}</h1>
                        <p class="text-center text-sm text-gray-500">{{ description }}</p>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
                    <slot />
                </div>
            </div>
        </div>
        <p class="text-gray-400 text-xs">
            © {{ new Date().getFullYear() }} Prefeitura Municipal de São Leopoldo
        </p>
    </div>
</template>
