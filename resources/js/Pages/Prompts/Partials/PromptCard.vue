<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({ prompt: Object })

function readableDate(d) {
    let date = new Date(d);

    return date.toDateString();
}
</script>

<template>
    <Link class="grid grid-rows-auto gap-2" :href="'prompts/' + prompt.id">

    <span>ID: {{ prompt.id }}</span>
    <span>Date: {{ readableDate(prompt.created_at) }}</span>
    </Link>


    <div class="mt-2">Prompt:</div>
    <div class="grid grid-flow-col auto-cols-max gap-2">{{ prompt.body }}
    </div>
</template>