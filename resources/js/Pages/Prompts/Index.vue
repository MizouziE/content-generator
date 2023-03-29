<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Modal from '@/Components/Modal.vue';
import Form from '@/Pages/Prompts/Partials/Form.vue'
import PromptCard from '@/Pages/Prompts/Partials/PromptCard.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { reactive } from 'vue';

defineProps({ prompts: Object });

const modal = reactive({ open: false })

function openModal() {
    modal.open = true;
}

function close(event) {
    modal.open = false;
}

</script>

<template>
    <AppLayout title="Prompts">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Prompts
            </h2>
        </template>

        <div class="py-12">
            <div class="flex justify-center max-w-7xl mx-auto sm:px-6 lg:px-8 gap-4">
                <div class="flex flex-col space-y-4">
                    <div class="flex justify-between mb-2">
                        <span class="font-semibold text-gray-600">Click on a card to see more details</span>
                        <PrimaryButton class="mx-2" :onClick="openModal" :type="button">
                            Add New Prompt
                        </PrimaryButton>
                    </div>
                    <div class="bg-white p-4 overflow-hidden shadow-xl sm:rounded-lg" v-for="prompt in prompts">
                        <PromptCard :prompt="prompt" />
                    </div>
                </div>
            </div>
        </div>
        <Modal :show="modal.open" @close="close">
            <Form />
        </Modal>
    </AppLayout>
</template>