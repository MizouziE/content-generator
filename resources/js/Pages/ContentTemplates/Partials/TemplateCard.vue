<script setup>
import SecondaryButton from '../../../Components/SecondaryButton.vue';
import { Link } from '@inertiajs/vue3';

defineProps({ template: Object })

function readableDate(d) {
    let date = new Date(d);

    return date.toDateString();
}
</script>

<template>
    <Link class="grid grid-rows-auto gap-2" :href="'/content-templates/' + template.id">

    <span>ID: {{ template.id }}</span>
    <span>Date: {{ readableDate(template.created_at) }}</span>
    <span>Prompts: <ul class="list-disc list-inside"><span v-for="prompt in JSON.parse(template.prompts)">
                <li>{{ prompt }}</li>
            </span></ul></span>
    </Link>


    <div class="mt-2">Content:</div>
    <div class="grid grid-flow-col auto-cols-max gap-2">

        <div class="my-2 " v-for="content in template.content.slice(0, 4)">

            <Link :href="'content/' + content.id">
            <SecondaryButton>
                {{ content.body.slice(0, 20) }}
            </SecondaryButton>
            </Link>
        </div>
    </div>
</template>