<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({ prompt: Object })

function readableDate(d) {
    let date = new Date(d);

    return date.toDateString();
}
</script>

<template>
    <AppLayout title="Prompt Preview">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Prompt Preview: {{ prompt.id }}
            </h2>
            <p class="text-sm text-gray-600 leading-tight mt-2">Created: {{ readableDate(prompt.created_at) }}</p>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="flex grid grid-rows-auto gap-2 p-12" v-html="prompt.body"></div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>