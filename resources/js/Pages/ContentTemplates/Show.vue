<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';

defineProps({ template: Object });

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
                <Link :href="route('contentTemplates')" class="font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                Back to List
                </Link>
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-12 mt-4">

                    <div class="" v-for="(info, index) in template">
                        <span class="font-bold">{{ index }}:</span> {{ info }}
                    </div>


                </div>
            </div>
        </div>

    </AppLayout>
</template>
