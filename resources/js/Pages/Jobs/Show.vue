<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import AddButton from '@/Components/AddButton.vue';
import FormSection from '@/Components/FormSection.vue';
import { useForm } from '@inertiajs/vue3'
import { ref } from 'vue';

const props = defineProps({ user: Object })

const form = useForm({
    columns: [''],
    prompts: [''],
    csv: null,
    userId: props.user.id,
})

// const file = ref<File | null>();

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
    form.reset('csv')
    form.clearErrors()
}

function submitForm() {
    form.submit('post', '/jobs')
}

</script>

<template>
    <AppLayout title="Jobs">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Jobs
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                    <FormSection>
                        <template #title>
                            <div class="pt-12 px-12">
                                Configure New Job
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
                            <!-- password -->
                            <label for="csv">CSV file:</label>
                            <input type="file" v-on:change="form.csv">
                            <div v-if="form.errors.csv">{{ form.errors.csv }}</div>
                        <!-- remember me -->
                        <input type="hidden" v-model="form.userId">
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
                                Create New Job
                            </PrimaryButton>
                        </div> -->

        <!-- <template>
                            <DialogModal v-if="$page.props.open">
                                                    <template #title>
                                                        Create New Job
                                                    </template>

                                                    <template #content>
                                                        Form goes here...
                                                    </template>

                                                </DialogModal>
                                            </template> -->


    </AppLayout>
</template>
