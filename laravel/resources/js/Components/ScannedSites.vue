<script setup lang="ts">
import axios from "axios";
import { onMounted, ref } from "vue";
import { inject, Ref } from "vue";

const scannedSites = inject("scannedSites") as Ref<Screenshot[]>;
const isFullImgOpen = ref(false);
const selectedImage = ref<Screenshot | null>(null);

function openModal(site: Screenshot) {
    selectedImage.value = site;
    isFullImgOpen.value = true;
}

function closeModal() {
    isFullImgOpen.value = false;
    selectedImage.value = null;
}
</script>

<template>
    <div class="container mx-auto px-4 py-8 overflow-auto min-h-72">
        <div class="flex flex-wrap -mx-2">
            <div
                v-for="site in scannedSites"
                :key="site.id"
                class="w-full md:w-1/2 lg:w-1/3 px-2 mb-4"
            >
                <div
                    class="site-card bg-white rounded-lg shadow-md overflow-hidden transition-transform duration-300 hover:scale-105 cursor-pointer"
                    @click="openModal(site)"
                >
                    <div class="h-48 overflow-hidden relative">
                        <img
                            :src="site.file_path"
                            :alt="site.name"
                            class="absolute inset-0 w-full h-full object-cover"
                        />
                    </div>

                    <div class="p-2">
                        <p class="text-sm font-semibold text-gray-700 truncate">
                            {{ site.name }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div
            v-if="isFullImgOpen"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75 p-4"
            @click.self="closeModal"
        >
            <div
                class="relative max-w-4xl w-full max-h-[90vh] overflow-auto bg-white rounded-lg shadow-2xl"
            >
                <img
                    v-if="selectedImage"
                    :src="selectedImage.file_path"
                    :alt="selectedImage.name"
                    class="w-full"
                />
                <div
                    class="absolute top-0 left-0 right-0 p-4 bg-gradient-to-b from-black to-transparent"
                >
                    <h3 class="text-white text-xl font-semibold">
                        {{ selectedImage?.url }}
                    </h3>
                </div>
                <button
                    @click="closeModal"
                    class="absolute top-3 right-2 text-white bg-red-500 rounded-full w-5 h-5 p-3 hover:bg-red-600"
                ></button>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Add your component-specific styles here */
</style>
