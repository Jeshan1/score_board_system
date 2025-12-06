<template>

  <div class="flex justify-between items-center mx-10 my-10">
    <h1 class="text-3xl font-bold">Dashboard</h1>
  </div>
  
    <!-- Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mx-10 my-10">
      <!-- Total Players -->
      <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-xl shadow-xl p-8 transform hover:scale-105 transition duration-300">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-blue-100 text-lg font-medium">Total Players</p>
            <p class="text-5xl font-bold mt-2">{{ totalEntity.total_players }}</p>
          </div>
          <div class="text-6xl opacity-30">
            <!-- Player Icon -->
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
          </div>
        </div>
      </div>

      <!-- Total Referees -->
      <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-xl shadow-xl p-8 transform hover:scale-105 transition duration-300">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-green-100 text-lg font-medium">Total Referees</p>
            <p class="text-5xl font-bold mt-2">{{ totalEntity.total_referees }}</p>
          </div>
          <div class="text-6xl opacity-30">
            <!-- Whistle Icon -->
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </div>
      </div>

      <!-- Total Teams -->
      <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-xl shadow-xl p-8 transform hover:scale-105 transition duration-300">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-purple-100 text-lg font-medium">Total Teams</p>
            <p class="text-5xl font-bold mt-2">{{ totalEntity.total_teams }}</p>
          </div>
          <div class="text-6xl opacity-30">
            <!-- Shield Icon -->
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                d="M12 14l9-5-9-5-9 5 9 5z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                d="M12 14l9-5-9-5-9 5 9 5zM12 14v7a2 2 0 002 2h4a2 2 0 002-2v-7" />
            </svg>
          </div>
        </div>
      </div>
    </div>
    <!-- team statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <div class="bg-white shadow-md rounded-xl p-6 mb-6 mx-10 my-10" v-for="stat in matchStat" :key="stat.id">
            <h3 class="text-xl font-bold mb-4 text-center">Team Statistics</h3>
            <div class="grid grid-cols-3 gap-6 text-center" >
                <div>
                    <div class="text-3xl font-bold text-blue-600">{{stat.team_a}}</div>
                </div>
                <div class="text-2xl font-semibold">VS</div>
                <div>
                    <div class="text-3xl font-bold text-red-600">{{ stat.team_b }}</div>
                </div>
                <div>
                    <div class="text-3xl font-bold text-blue-600">{{ stat.team_a_goals }}</div>
                    <div class="text-gray-600">Goals</div>
                </div>
                <div class="text-2xl font-semibold">VS</div>
                <div>
                    <div class="text-3xl font-bold text-red-600">{{ stat.team_b_goals }}</div>
                    <div class="text-gray-600">Goals</div>
                </div>

                <div>
                    <div class="text-2xl font-bold text-blue-600">{{ stat.team_a_fouls }}</div>
                    <div class="text-gray-600">Fouls</div>
                </div>
                <div></div>
                <div>
                    <div class="text-2xl font-bold text-red-600">{{ stat.team_b_fouls }}</div>
                    <div class="text-gray-600">Fouls</div>
                </div>
            </div>
        </div>
    </div>

    <!-- all matches  -->
  <div class="bg-white rounded-xl shadow-lg overflow-hidden mx-10 my-10">
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <!-- Table Header -->
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
              Date & Time
            </th>
            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
              Home Team
            </th>
            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
              Score
            </th>
            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
              Away Team
            </th>
            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
              Status
            </th>
            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
              Referee
            </th>
          </tr>
        </thead>

        <!-- Table Body -->
        <tbody class="bg-white divide-y divide-gray-200">
          <tr class="hover:bg-gray-50 transition duration-150" v-if="matches.length > 0" v-for="match in matches" :key="matches.id">
            <!-- Date & Time -->
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
              <div class="font-medium">{{ match.date}}</div>
              <div class="text-gray-500 text-xs">{{ match.time }}</div>
            </td>

            <!-- Home Team -->
            <td class="px-6 py-4 text-sm font-semibold text-blue-700">
              {{ match.home_team }}
            </td>

            <!-- Score -->
            <td class="px-6 py-4 text-center">
              <div class="text-2xl font-bold text-gray-800">
                <span >
                  {{ match.home_score }} - {{ match.away_score }}
                </span>
              </div>
            </td>

            <!-- Away Team -->
            <td class="px-6 py-4 text-sm font-semibold text-red-700">
              {{match.away_team}}
            </td>

            <!-- Status Badge -->
            <td class="px-6 py-4 text-center">
              <span 
                class="inline-flex px-3 py-1 rounded-full text-xs font-medium uppercase tracking-wider"
              >
                {{ match.status }}
              </span>
            </td>

            <!-- Referee -->
            <td class="px-6 py-4 text-center text-sm text-gray-700">
              {{ match.referee }}
            </td>

          </tr>

          <!-- No Data -->
          <tr v-if="matches.length === 0">
            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
              No matches found
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>

import {ref, onMounted, computed} from 'vue'
import api from "../../axios";
import { useStore } from 'vuex';

const matchStat = ref([])
const totalEntity = ref({})
const matches = ref([]);
const store = useStore();
const token = computed(() => store.state.token);

onMounted(() => {
    fetchMatchStat();
    getTotalEnity();
    getMatches();
})

function fetchMatchStat()
{
    api.get('/api/fetch-all-team-statistics',{
        headers: { Authorization: `Bearer ${token.value}` }
    }).then(res => {
        matchStat.value = res.data.data
    })
    .catch(err => {
        console.log(err)
    })
}

function getTotalEnity(){
    api.get('/api/get-total-entity',{
        headers: { Authorization: `Bearer ${token.value}` }
    }).then(res => {
        totalEntity.value = res.data.data
    })
    .catch(err => {
        console.log(err)
    })
   
}

function getMatches(){
    api.get('/api/get-matches',{
        headers: { Authorization: `Bearer ${token.value}` }
    }).then(res => {
        matches.value = res.data.data
    })
    .catch(err => {
        console.log(err)
    })
}
</script>