<template>
    <section class="max-w-7xl mx-auto p-6">
      <h3 class="text-2xl font-semibold mb-4">Matches</h3>
  
      <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 rounded-lg">
          <thead class="bg-gray-100">
            <tr>
              <th class="py-2 px-4 border-b">Date</th>
              <th class="py-2 px-4 border-b">Start Time</th>
              <th class="py-2 px-4 border-b">Home Team</th>
              <th class="py-2 px-4 border-b">Away Team</th>
              <th class="py-2 px-4 border-b">Score</th>
              <th class="py-2 px-4 border-b">Referee</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="match in matches" :key="match.id" class="hover:bg-gray-50">
              <td class="py-2 px-4 border-b">{{ match.date }}</td>
              <td class="py-2 px-4 border-b">{{ match.start_time }}</td>
              <td class="py-2 px-4 border-b">{{ match.home_team }}</td>
              <td class="py-2 px-4 border-b">{{ match.away_team }}</td>
              <td class="py-2 px-4 border-b">{{ match.home_score }} - {{ match.away_score }}</td>
              <td class="py-2 px-4 border-b">{{ match.referee }}</td>
            </tr>
          </tbody>
        </table>
  
        <div v-if="matches.length === 0" class="text-center text-gray-500 mt-6">
          No matches available.
        </div>
      </div>
    </section>
  </template>
  
  <script>
  export default {
    name: "MatchesTable",
    props: {
      matches: {
        type: Array,
        required: true,
      },
    },
  };
  </script>
  