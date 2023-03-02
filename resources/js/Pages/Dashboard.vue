<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Components/Welcome.vue';
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-12 flex flex-col gap-4">

                        <h1 class="text-xl font-semibold ">Welcome to the Content Generator</h1> 

                        <p>Provide a list of prompt templates and a spreadsheet with the expected values to generate multiple articles with one click.</p>

                        <img src="https://bestanimations.com/media/writing/388577157hand-writing-close-up-animated-gif.gif">
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
