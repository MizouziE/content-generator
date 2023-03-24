<script setup>
import AddButton from '@/Components/AddButton.vue';
import FormSection from '@/Components/FormSection.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { useForm } from '@inertiajs/vue3'
import { ref } from 'vue';
import { read, utils } from 'xlsx';

const headings = ref([]);

const form = useForm({
    columns: [''],
    prompts: [''],
    maxTokens: 500,
    csv: null,
})

async function readSpreadsheet(event) {
    event.preventDefault()

    let files = document.querySelectorAll('input[type="file"]')[0].files;
    if (files.length !== 1) return;

    // set limit of readble file: currently = 2.5MB
    if (files[0].size > 2500000) {
        // TODO: Do something more useful
        alert("file too big to read!");
        return;
    }

    const file = await files[0].arrayBuffer();

    const sheet = read(file);

    const json = utils.sheet_to_json(sheet.Sheets[sheet.SheetNames[0]], { header: 1 })

    // push only the heading row to ref headings variable

    updateColumnNames(json[0]);
}

function updateColumnNames(columnNames) {

    if (checkIfDuplicateExists(columnNames)) {
        alert("Provided spread contains duplicate column names!\n\nPlease use only uniquely named columns and retry.");

        //clear input
        clearSpreadsheet()

        return;
    }

    headings.value = columnNames;

}

function checkIfDuplicateExists(arr) {
    return new Set(arr).size !== arr.length
}

function clearSpreadsheet() {
    //clear input
    document.getElementById('spreadsheet').value = null;
    // clear columns
    headings.value = [];
}

function addPrompt() {
    form.prompts.push('')
}

function clearForm() {
    form.reset('columns')
    form.reset('prompts')
    form.reset('maxTokens')
    form.reset('csv')
    form.clearErrors()
}

function submitForm() {
    // console.log(form)
    form.submit('post', '/content-templates', {
        forceFormData: true,
        onSuccess: () => form.reset(),
    })
}

</script>

<template>
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

        <template #form >
            <div class="grid gap-y-10">
            <!-- csv -->
            <div class="">

                <label for="csv">CSV file:</label>
                <!-- Drag and Drop, need to figure out how to make it work -->
            <!-- <label
                        class="flex justify-center w-full h-32 px-4 transition bg-white border-2 border-gray-300 border-dashed rounded-md appearance-none cursor-pointer hover:border-gray-400 focus:outline-none">
                        <span class="flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                            <span class="font-medium text-gray-600">
                                Drop files to Attach, or
                                <span class="text-blue-600 underline">browse</span>
                            </span>
                        </span>
                        <input class="hidden" type="file"
                        :onChange="(event) => readSpreadsheet(event)" />
                    </label> -->
            <!-- No-Nonsense file upload -->
            <div class="flex justify-between items-center">
                <input type="file" id="spreadsheet" :onChange="(event) => readSpreadsheet(event)" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />
                <SecondaryButton v-if="headings.length" :onClick="clearSpreadsheet" type="button">Remove</SecondaryButton>
            </div>
            <progress v-if="form.progress" :value="form.progress.percentage" max="100">
                {{ form.progress.percentage }}%
            </progress>
            <div v-if="form.errors.csv">{{ form.errors.csv }}</div>
        </div>
            <!-- columns -->
            <div class="">

                <div class="flex justify-between">
                    <label for="columns">Columns:</label>
                <div v-if="headings.length != 0" class="">Selected</div>
            </div>
            <div class="text-gray-500 text-sm" v-if="headings.length == 0">Columns will appear here when a file is
                uploaded...</div>
                <div class="flex w-full justify-between" v-else v-for="heading in headings">
                    <label for="column">{{ heading }}</label>
                    <input type="checkbox" :value="heading" v-model="form.columns">
                    <!-- </option> -->
                </div>
                <div v-if="form.errors.columns">{{ form.errors.columns }}</div>
            </div>
            <!-- prompts -->
            <div class="">

                <label for="prompts">Prompts:</label>
                <div class="flex gap-2" v-for="(prompt, index) in form.prompts" :key="index">
                    <input class="grow rounded min-h-max" id="prompts" type="text" v-model="form.prompts[index]">
                    <AddButton type="button" :onclick="addPrompt">+</AddButton>
                </div>
                <div v-if="form.errors.prompts">{{ form.errors.prompts }}</div>
            </div>
            <!-- max tokens -->
            <div class="">

                <div class="flex justify-between items-center">
                    <label for="max-tokens">Max Tokens:</label>
                    <div class="text-sm border rounded p-2">{{ form.maxTokens }}</div>
                </div>
                <input id="max-tokens" type="range" min="50" max="4000" v-model="form.maxTokens">
                <div v-if="form.errors.maxTokens">{{ form.errors.maxTokens }}</div>
            </div>
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