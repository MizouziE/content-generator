<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';

defineProps({ templates: Object });

</script>

<template>
<AppLayout title="Content Templates">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Content Templates
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex bg-white overflow-hidden shadow-xl sm:rounded-lg gap-4">
                    <div class="flex grid grid-rows-auto gap-2" v-for="template in templates">
                        <Link :href="'content-templates/' + template.id">

                            <span>ID: {{ template.id }}</span>
                            <span>Date: {{ template.created_at }}</span>
                            <span>Prompts: <ul class="list-disc"><span v-for="prompt in JSON.parse(template.prompts)"><li>{{ prompt }}</li></span></ul></span>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
</AppLayout>
</template>