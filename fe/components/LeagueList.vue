<template>
  <div class="p-4">
    <h2 class="text-xl font-bold mb-4">Leagues</h2>
    <ul>
      <li v-for="league in leagues" :key="league.id" class="mb-2">
        <NuxtLink :to="`/league/${league.id}`" class="text-blue-500 hover:underline">
          {{ league.name }}
        </NuxtLink>
      </li>
    </ul>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import { fetchLeagues } from '../services/api';

export default {
  setup() {
    const leagues = ref([]);

    onMounted(async () => {
      leagues.value = await fetchLeagues();
    });

    return { leagues };
  },
};
</script>