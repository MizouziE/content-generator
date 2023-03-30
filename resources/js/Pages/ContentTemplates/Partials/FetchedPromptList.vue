<template>
    <Menu as="div" class="relative inline-block text-left">
        <div>
            <MenuButton>
                <SearchButton class="grow" type="button" @click="searchPrompt(prompt, index)"><svg fill="currentColor"
                    version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
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
            </MenuButton>
        </div>

        <transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95"
            enter-to-class="transform opacity-100 scale-100" leave-active-class="transition ease-in duration-75"
            leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
            <MenuItems
                class="absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                <div class="py-1">
                    <MenuItem v-slot="{ active }" v-for="singlePrompt in fetchedPrompts">
                    <button @click="pushPrompt(singlePrompt.body)"
                        :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm']">{{ singlePrompt.body }}</button>
                    </MenuItem>
                </div>
            </MenuItems>
        </transition>
</Menu></template>
  
<script setup>
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import { ref } from 'vue';
import SearchButton from '../../../Components/SearchButton.vue'

const props = defineProps({ prompt: Object, index: Number})

const fetchedPrompts = ref([]);

const searchIndex = ref(0)

async function searchPrompt(search, index) {
    console.log(search, index);

    let prompts = await fetch('/api/prompts?search=' + search )
                            .then((response) => response.json());

    console.log(prompts);

    fetchedPrompts.value = prompts;
    searchIndex.value = index;
}

function pushPrompt(toPush) {
    console.log(toPush)
    

}
</script>
  