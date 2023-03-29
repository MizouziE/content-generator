<script setup>
import AddButton from '@/Components/AddButton.vue';
import FormSection from '@/Components/FormSection.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { useForm } from '@inertiajs/vue3'

const form = useForm({
    body: '',
})

function clearForm() {
    form.reset('prompt')
    form.clearErrors()
}

function submitForm() {
    form.submit('post', '/prompts', {
        onSuccess: () => clearForm()
    })
}

</script>

<template>
    <FormSection>
        <template #title>
            <div class="pt-12 px-12">
                Configure New Prompt
            </div>
        </template>

        <template #description>
            <div class="pb-12 px-12">
                Please use this form to create a prompt template that can be used later in a Content Template.
            </div>
        </template>

        <template #form>
            <div class="grid gap-y-2">
                <!-- prompt -->
                <label for="prompt">Prompt:</label>
                <textarea class="grow rounded min-h-max" id="prompt" rows="4" cols="20" v-model="form.body"></textarea>
                <div v-if="form.errors.body">{{ form.errors.body }}</div>
            </div>
        </template>

        <template #actions>
            <SecondaryButton :onClick="clearForm">
                Cancel
            </SecondaryButton>

            <PrimaryButton :onclick="submitForm">
                Submit
            </PrimaryButton>
        </template>
    </FormSection>
</template>