<template>
    <div class="p-6">
      <h1 class="text-3xl font-bold mb-6">Leagues</h1>
  
      <button
        @click="openModal()"
        class="mb-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
      >
        Create League
      </button>
  
      <!-- League Table -->
      <table class="min-w-full bg-white border rounded-lg">
        <thead>
          <tr class="bg-gray-200 text-gray-700">
            <th class="py-2 px-4 border">Name</th>
            <th class="py-2 px-4 border">Start Date</th>
            <th class="py-2 px-4 border">End Date</th>
            <th class="py-2 px-4 border">Actions</th>
          </tr>
        </thead>
        <tbody>
          <template v-if="leagues.length > 0">
            <tr v-for="league in leagues" :key="league.id" class="text-gray-700">
            <td class="py-2 px-4 border">{{ league.name }}</td>
            <td class="py-2 px-4 border text-center">{{ league.start_date }}</td>
            <td class="py-2 px-4 border text-center">{{ league.end_date }}</td>
            <td class="py-2 px-4 border space-x-2">
              <button
                @click="openModal(league)"
                class="bg-yellow-400 px-2 py-1 rounded hover:bg-yellow-500"
              >
                Edit
              </button>
              <button
                @click="deleteLeague(league.id)"
                class="bg-red-500 px-2 py-1 rounded hover:bg-red-600 text-white"
              >
                Delete
              </button>
            </td>
          </tr>
          </template>
          <template v-else>
            <tr>
              <td colspan="4" class="py-2 px-4 border text-center">
                No leagues available.
              </td>
            </tr>
          </template>
        </tbody>
      </table>
  
      <!-- League Modal -->
      <template v-if="showModal">
        <LeagueModal
          :show="showModal"
          :league="selectedLeague"
          @close="closeModal"
          @submit="handleSubmit"
        />
      </template>
    </div>
  </template>
  
  <script setup>
  import { ref, reactive, onMounted, computed } from "vue";
  import LeagueModal from "./LeagueModal.vue";
  import api from "../../../axios";
  
  import { useStore } from "vuex";
  const store = useStore();
  const token = computed(() => store.state.token);
  
  const leagues = ref([]);
  
  const showModal = ref(false);
  const selectedLeague = ref(null);
  
  const fetchLeagues = async () => {
    console.log("token",token.value);
    try {
      const response = await api.get("/api/leagues",{
        headers: { Authorization: `Bearer ${token.value}` }
      });
  
      leagues.value = response.data.data;
    } catch (error) {
      console.error("Error fetching leagues:", error);
    }
  }
  
  
  // Open modal
  const openModal = (league = null) => {
    selectedLeague.value = league;
    console.log("selectedLeague",selectedLeague);
    showModal.value = true;
  };
  
  // Close modal
  const closeModal = () => {
    showModal.value = false;
  };
  
  // Handle form submit from modal
  const handleSubmit = async (leagueData) => {
    if (leagueData.isEdit) {
      const response = await api.put(`/api/leagues/${leagueData.id}`, leagueData, {
        headers: { Authorization: `Bearer ${token.value}` }
      });
  
    } else {
      // Create new league
       const response = await api.post("/api/leagues", leagueData, {
         headers: { Authorization: `Bearer ${token.value}` }
       })
    }
    setTimeout(() => {
      fetchLeagues();
    }, 1000);
  };
    
  // Delete league
  const deleteLeague = async (id) => {
       const response = await api.delete(`/api/leagues/${id}`, {
         headers: { Authorization: `Bearer ${token.value}` }
       })
  
       console.log("response",response.data.message);
    
    setTimeout(() => {
        fetchLeagues();
      }, 500);
  };
  
  onMounted(() => {
    fetchLeagues();
  });
  
  
  
  </script>
  