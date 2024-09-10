<script setup lang="ts">
import { inject } from 'vue';
import Loader from './Loader/Loader.vue';

// Injecter l'état partagé
const scanningSites = inject('scanningSites');
</script>

<template>
    <div>
        <ul v-if="scanningSites && scanningSites.length > 0" class="space-y-2">
            <li v-for="site in scanningSites" :key="site.id" class="flex items-center bg-blue-100 p-3 rounded-md">
                <div class="animate-spin mr-3">
                    <Loader></Loader>
                </div>
                <span class="font-medium text-blue-800">{{ site.name }}</span>
            </li>
        </ul>
        <p v-else class="text-gray-500 italic">No sites currently scanning</p>
    </div>
</template>