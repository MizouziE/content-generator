<script setup>
import AddButton from '@/Components/AddButton.vue';
import SearchButton from '@/Components/SearchButton.vue';
import FormSection from '@/Components/FormSection.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { useForm } from '@inertiajs/vue3'
import { ref } from 'vue';
import { read, utils } from 'xlsx';
import FetchedPromptList from './FetchedPromptList.vue';

const emit = defineEmits(['close-modal'])

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

function clearForm() {
    form.reset('columns')
    form.reset('prompts')
    form.reset('maxTokens')
    form.reset('spreadsheet')
    clearSpreadsheet()
    form.clearErrors()
    emit('close-modal')
}

function submitForm() {
    form.submit('post', '/content-templates', {
        forceFormData: true,
        onSuccess: () => {
            clearForm();
            emit('close-modal');
        }
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
                            <FetchedPromptList :prompt="prompt" :index="index" @push-prompt="(payload) => {
                                form.prompts[index] = payload
                            }">
                                    
                            </FetchedPromptList>
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