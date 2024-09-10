<script setup lang="ts">
import { inject, ref } from "vue";
import { router } from "@inertiajs/vue3";
import axios, { AxiosError } from "axios";
import Ok from "./Button/Ok.vue";
import Toast from "./Toast.vue";
import Cross from "./Button/Cross.vue";
import { toast } from "vue3-toastify";
import { useToast } from "vue-toast-notification";

const message = ref("Hello, Vue!");

const url = ref("");
const name = ref("");

console.log(name.value);


const addScanningSite = inject('addScanningSite');
const scannedSites = inject('scannedSites');

async function submit() {
    console.log("URL:", url.value);
    console.log("Name:", name.value);
    try {
        const res = await axios.post("/api/jobs", {
            url: url.value,
            name: name.value,
        });
        if (res.status === 200) {
            useToast().success(`${name.value} has been sent to queue`);
            console.log("scanned site avant", scannedSites.value);
            if (addScanningSite) {
                addScanningSite({
                    name: name.value, url: url.value
                });

            }
            console.log("scanned site apres", scannedSites.value);
        }
        else {
            useToast().error("Failed to submit job ", res.data);
            console.log("res -= ", res.data);
        }

    } catch (error) {
        // console.log(" l'erreurrrr = --" , JSON.stringify(error));
        console.error("Error submitting job:", error.response.data.error);
        const err = error.response.data.error;
        useToast().error("Failed to submit job " + err);
    }
}

let isOpen = ref(true);

function closeToast() {
    isOpen.value = false;
}

</script>

<template>
    <!-- <Toast v-if="isOpen" text="Website sent !" :close-toast="closeToast"><Cross></Cross ></Toast> -->
    <div class="flex items-center">
        <form @submit.prevent="submit" class="max-w-sm mx-auto w-full">
            <div class="mb-5 w-full">
                <label
                    for="text"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                    >URL</label
                >
                <input
                    v-model="url"
                    type="url"
                    id="url"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="http://example.com"
                    required
                />
            </div>
            <div class="mb-5">
                <label
                    for="name"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                    >Name</label
                    >
                    <input
                    placeholder="Name"
                    v-model="name"
                    type="text"
                    id="name"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    required
                />
            </div>
            <div class="flex items-start mb-5">
                <button
                    type="submit"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                >
                    Submit
                </button>
            </div>
        </form>
    </div>
</template>
