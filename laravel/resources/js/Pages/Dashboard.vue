<script setup lang="ts">
import Layout from "../Layouts/Layout.vue";
import ScannedSites from "../Components/ScannedSites.vue";
import UrlForm from "../Components/UrlForm.vue";
import { onMounted, provide, ref } from "vue";
import { useToast } from "vue-toast-notification";
import "vue-toast-notification/dist/theme-sugar.css";
import ScanningSites from "../Components/ScanningSites.vue";
import { usePage } from "@inertiajs/vue3";
import axios from "axios";

const scannedSites = ref<Screenshot[]>([]);
const scanningSites = ref<ScanningSite[]>([]);

const addScanningSite = (site: ScanningSite) => {
    scanningSites.value.push(site);
};

provide("scanningSites", scanningSites);
provide("addScanningSite", addScanningSite);

const updateScreenshot = (newScreenshot: Screenshot) => {
    const index = scannedSites.value.findIndex(
        (site) => site.id === newScreenshot.id
    );
    if (index !== -1) {
        scannedSites.value[index] = newScreenshot;
    } else {
        scannedSites.value.push(newScreenshot);
    }
};

const fetchScannedSites = async () => {
    try {
        const { data } = await axios.get<Screenshot[]>("/api/scannedSites");
        scannedSites.value = data;
    } catch (error) {
        console.error("Failed to fetch scanned sites:", error);
    }
};

provide("scannedSites", scannedSites);
provide("updateScreenshot", updateScreenshot);

onMounted(async () => {
    await fetchScannedSites();

    try {
        console.log("Listening for NewScan events");

        window.Echo.channel(`zebiscan`).listen(
            "NewScan",
            (response: NewScan) => {
                console.log("Received NewScan event", response.temporaryUrl);
                response.screenshot.file_path = response.temporaryUrl;
                updateScreenshot(response.screenshot);
                scanningSites.value = scanningSites.value.filter(
                    (site) => site.url !== response.screenshot.url
                );
            }
        );
    } catch (error) {
        console.error("Error setting up Echo listener:", error);
    }
});
</script>

<template>
    <Layout class="bg-gray-100 min-h-screen">
        <div class="container mx-auto px-4 py-8">
            <h1 class="text-3xl font-bold mb-8 text-gray-800">Dashboard</h1>

            <div class="flex flex-col md:flex-row gap-8">
                <div class="flex-1 bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-2xl font-semibold mb-4 text-gray-700">
                        Add New Site
                    </h2>
                    <UrlForm />
                </div>

                <div
                    class="flex-1 bg-white rounded-lg shadow-md p-6 overflow-auto pb-2.5"
                >
                    <h2 class="text-2xl font-semibold mb-4 text-gray-700">
                        Scanning Sites
                    </h2>
                    <ScanningSites />
                </div>
            </div>

            <div class="mt-8">
                <h2 class="text-3xl font-bold mb-6 text-gray-800">
                    Scanned Sites
                </h2>
                <ScannedSites />
            </div>
        </div>
    </Layout>
</template>
