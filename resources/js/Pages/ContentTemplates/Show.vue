<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';
import SecondaryButton from '../../Components/SecondaryButton.vue';

defineProps({ template: Object });

function readableDate(d) {
    let date = new Date(d);

    return date.toDateString();
}
</script>

<template>
    <AppLayout title="Content Templates">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Content Template
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Link :href="route('contentTemplates')"
                    class="font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                Back to List
                </Link>
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-12 mt-4">

                    <div class="grid grid-rows-auto gap-2">

                        <span>ID: {{ template.id }}</span>
                        <span>Date: {{ readableDate(template.created_at) }}</span>
                        <span>Prompts: <ul class="list-disc list-inside"><span
                                    v-for="prompt in JSON.parse(template.prompts)">
                                    <li>{{ prompt }}</li>
                                </span></ul></span>
                    </div>

                    <div v-if="template.content" class="mt-2">Content:
                        <div class="my-2" v-for="content in template.content">

                            <Link :href="route('content', { id: content.id })">
                            <SecondaryButton>
                                {{ content.body.slice(0, 40) }}
                            </SecondaryButton>
                            </Link>
                        </div>
                    </div>
                    <div v-else class="mt-2">
                        Content still being generated, please wait...
                    </div>

                </div>
            </div>
        </div>

    </AppLayout>
</template>
