<template>
  <div>
    <nav class="h-1/6 flex justify-center items-center text-5xl">
      <!-- Navigation Bar -->
      <img src="/sbacknobg.png" alt="Logo" class="h-40 w-auto" />
    </nav>
    <main>
      <slot></slot> <!-- This is where the page content will be injected -->
    </main>
    <footer>
      <!-- Footer -->
    </footer>
  </div>
</template>

<script setup>
</script>

<style scoped>
/* Add your layout-specific styles here */
</style>
