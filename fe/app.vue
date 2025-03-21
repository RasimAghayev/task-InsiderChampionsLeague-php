<template>
  <div>
    <NuxtRouteAnnouncer />

    <Navbar />
    <main class="container mx-auto p-4">
      <NuxtPage />
    </main>
  </div>
</template>

<script setup>
import Navbar from "~/components/Navbar.vue";
</script>

<style>
@tailwind base;
@tailwind components;
@tailwind utilities;
</style>