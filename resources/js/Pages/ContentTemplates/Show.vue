<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({ template: Object});

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
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-12">

                    <div class="" v-for="(info, index) in template">
                        <span class="font-bold">{{ index }}:</span> {{ info }}
                    </div>


                </div>
            </div>
        </div>

    </AppLayout>
</template>
