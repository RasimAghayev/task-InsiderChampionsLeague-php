<template>
  <div>
    <div class="p-4">
      <template v-if="league">
        <h2 class="text-xl font-bold mb-4">{{ league.name }}</h2>
        <LeagueTable :table="league.league_table" />
      </template>
      <div v-else class="text-gray-500">Loading...</div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { fetchLeagueById } from '~/services/api';
import Navbar from '~/components/Navbar.vue';
import LeagueTable from '~/components/LeagueTable.vue';

export default {
  components: { Navbar, LeagueTable },
  setup() {
    const route = useRoute();
    const league = ref({});

    onMounted(async () => {
      try {
        const id = parseInt(route.params.id);
        const data = await fetchLeagueById(id);
        if (data) {
          league.value = data;
        } else {
          console.error('Not found league');
        }
      } catch (error) {
        console.error('Error API:', error);
      }
    });

    return { league };
  },
};
</script>