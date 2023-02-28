<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import AddButton from '@/Components/AddButton.vue';
import FormSection from '@/Components/FormSection.vue';
import { useForm } from '@inertiajs/vue3'


const form = useForm({
    columns: [''],
    prompts: [''],
    maxTokens: 50,
    csv: null,
})

function addColumn() {
    form.columns.push('')
}

function addPrompt() {
    form.prompts.push('')
}

// function onFileChanged($event) {
//             const target = $event.target;
//             if (target && target.files) {
//                 file.value = target.files[0];
//             }
//         }

function clearForm() {
    form.reset('columns')
    form.reset('prompts')
    form.reset('maxTokens')
    form.reset('csv')
    form.clearErrors()
}

function submitForm() {
    form.submit('post', '/content-templates', {
        forceFormData: true,
        onSuccess: () => form.reset(),
        })
}

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
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                    <FormSection>
                        <template #title>
                            <div class="pt-12 px-12">
                                Configure New Content Template
                            </div>
                        </template>

                        <template #description>
                            <div class="pb-12 px-12">
                                Please provide the needed information to construct prompts from which to generate articles.
                            </div>
                        </template>

                        <template #form>
                            <!-- columns -->
                            <label for="columns">Columns:</label>
                            <div class="flex gap-2"
                                v-for="(heading, index) in form.columns"
                                :key="index"
                            >
                                <input class="grow" id="columns" type="text" v-model="form.columns[index]">
                                <AddButton 
                                    type="button"
                                    :onclick="addColumn"
                                >+</AddButton>
                            </div>
                            <div v-if="form.errors.columns">{{ form.errors.columns }}</div>
                            <!-- prompts -->
                            <label for="prompts">Prompts:</label>
                            <div class="flex gap-2"
                                v-for="(prompt, index) in form.prompts"
                                :key="index"
                            >
                                <input class="grow min-h-max" id="prompts" type="text" v-model="form.prompts[index]">
                                <AddButton 
                                    type="button"
                                    :onclick="addPrompt"
                                    >+</AddButton>
                            </div>
                            <div v-if="form.errors.prompts">{{ form.errors.prompts }}</div>
                            <!-- max tokens -->
                            <label for="max-tokens">Max Tokens:</label>
                            <input id="max-tokens" type="number" v-model="form.maxTokens">
                            <div v-if="form.errors.maxTokens">{{ form.errors.maxTokens }}</div>
                            <!-- csv -->
                            <label for="csv">CSV file:</label>
                            <input type="file" @input="form.csv = $event.target.files[0]" />
                            <progress v-if="form.progress" :value="form.progress.percentage" max="100">
                            {{ form.progress.percentage }}%
                            </progress>
                            <div v-if="form.errors.csv">{{ form.errors.csv }}</div>
                    </template>

                    <template #actions>
                            <SecondaryButton
                                :onClick="clearForm"
                                >
                                Cancel
                        </SecondaryButton>

                        <PrimaryButton
                            :onclick="submitForm"
                            >
                            Submit
                        </PrimaryButton>
                    </template>
                </FormSection>

            </div>
        </div>
    </div>

        <!-- <div>
                <PrimaryButton 
                            @onClick="">
                                Create New ContentTemplate
                            </PrimaryButton>
                        </div> -->

        <!-- <template>
                            <DialogModal v-if="$page.props.open">
                                                    <template #title>
                                                        Create New ContentTemplate
                                                    </template>

                                                    <template #content>
                                                        Form goes here...
                                                    </template>

                                                </DialogModal>
                                            </template> -->


    </AppLayout>
</template>
