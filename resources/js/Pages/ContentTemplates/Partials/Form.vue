<script setup>
import AddButton from '@/Components/AddButton.vue';
import SearchButton from '@/Components/SearchButton.vue';
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
    spreadsheet: null,
})

async function readSpreadsheet(event) {
    event.preventDefault()

    let files = document.querySelectorAll('input[type="file"]')[0].files;
    if (files.length !== 1) return;

    // set limit of readble file: currently = 2.5MB
    if (files[0].size > 2500000) {
        // TODO: Do something more useful
        alert("file too big to read!");
        clearSpreadsheet();
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

async function searchPrompt(search, index) {
    console.log(search);

    let prompts = await fetch('/api/prompts?search=' + search )
                            .then((response) => response.json());

    console.log(prompts);
}

function clearForm() {
    form.reset('columns')
    form.reset('prompts')
    form.reset('maxTokens')
    form.reset('spreadsheet')
    clearSpreadsheet()
    form.clearErrors()
}

function submitForm() {
    form.submit('post', '/content-templates', {
        forceFormData: true,
        onSuccess: () => clearForm(),
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

        <template #form>
            <div class="grid gap-y-10">
                <!-- spreadsheet -->
                <div class="">

                    <label for="spreadsheet">Spreadsheet file:</label>
                    <div class="flex justify-between items-center">
                        <input type="file" id="spreadsheet" @input="form.spreadsheet = $event.target.files[0]"
                            :onChange="(event) => readSpreadsheet(event)"
                            accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />
                        <SecondaryButton v-if="headings.length" :onClick="clearSpreadsheet" type="button">Remove
                        </SecondaryButton>
                    </div>
                    <progress v-if="form.progress" :value="form.progress.percentage" max="100">
                        {{ form.progress.percentage }}%
                    </progress>
                    <div v-if="form.errors.spreadsheet">{{ form.errors.spreadsheet }}</div>
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
                    <div class="flex mt-2" v-for="(prompt, index) in form.prompts" :key="index">
                        <textarea class="grow rounded min-h-max" id="prompts" rows="4" cols="20"
                            v-model="form.prompts[index]"></textarea>
                        <div class="flex flex-col gap-2 ml-2">
                            <SearchButton class="grow" type="button" @click="searchPrompt(prompt, index)"><svg fill="currentColor" version="1.1" id="Capa_1"
                                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    viewBox="0 0 49.999 49.999" xml:space="preserve">
                                    <g>
                                        <g>
                                            <path
                                                d="M48.681,42.295l-8.925-8.904c-0.045-0.045-0.098-0.078-0.145-0.11c-0.802,1.233-1.761,2.405-2.843,3.487    c-1.081,1.082-2.255,2.041-3.501,2.845c0.044,0.046,0.077,0.1,0.122,0.144l8.907,8.924c1.763,1.76,4.626,1.758,6.383,0    C50.438,46.921,50.439,44.057,48.681,42.295z" />
                                            <path
                                                d="M35.496,6.079C27.388-2.027,14.198-2.027,6.089,6.081c-8.117,8.106-8.118,21.306-0.006,29.415    c8.112,8.105,21.305,8.105,29.413-0.001C43.604,27.387,43.603,14.186,35.496,6.079z M9.905,31.678    C3.902,25.675,3.904,15.902,9.907,9.905c6.003-6.002,15.77-6.002,21.771-0.003c5.999,6,5.997,15.762,0,21.774    C25.676,37.66,15.91,37.682,9.905,31.678z" />
                                            <path
                                                d="M14.18,22.464c-0.441-1.812-2.257-4.326-3.785-3.525c-1.211,0.618-0.87,3.452-0.299,5.128    c2.552,7.621,11.833,9.232,12.798,8.268C23.843,31.387,15.928,29.635,14.18,22.464z" />
                                        </g>
                                    </g>
                                </svg></SearchButton>
                            <AddButton class="grow" type="button" :onclick="addPrompt">+</AddButton>
                        </div>
                    </div>
                    <div v-if="form.errors.prompts">{{ form.errors.prompts }}</div>
                </div>
                <!-- max tokens -->
                <div class="">

                    <div class="flex justify-between items-center">
                        <label for="max-tokens">Max Tokens:</label>
                        <div class="text-sm border rounded p-2">{{ form.maxTokens }}</div>
                    </div>
                    <input class="w-full" id="max-tokens" type="range" min="50" max="4000" v-model="form.maxTokens">
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
</FormSection></template>