<template>
  <div class="p-4">
    <h2 class="text-xl font-bold mb-4">League Table</h2>
    <table class="w-full border-collapse">
      <thead>
      <tr class="bg-gray-200">
        <th>Team</th>
        <th>P</th>
        <th>W</th>
        <th>D</th>
        <th>L</th>
        <th>GF</th>
        <th>GA</th>
        <th>GD</th>
        <th>Pts</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="row in table" :key="row.name" class="border-b">
        <td>{{ row.name }}</td>
        <td class="text-center">{{ row.played }}</td>
        <td class="text-center">{{ row.won }}</td>
        <td class="text-center">{{ row.drawn }}</td>
        <td class="text-center">{{ row.lost }}</td>
        <td class="text-center">{{ row.goals_for }}</td>
        <td class="text-center">{{ row.goals_against }}</td>
        <td class="text-center">{{ row.goal_difference }}</td>
        <td class="text-center font-bold">{{ row.points }}</td>
      </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  props: {
    table: Array,
  },

};
</script>