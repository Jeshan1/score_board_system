<template>
  <Table :title="'Leagues'" :columns="columns" :data="leagues" @open-modal="openModal" @delete="deleteLeague" />
  <!-- League Modal -->
  <template v-if="showModal">
      <LeagueModal
        :show="showModal"
        :league="selectedLeague"
        @close="closeModal"
        @submit="handleSubmit"
      />
    </template>
</template>

<script setup>
import { ref, reactive, onMounted, computed } from "vue";
import Table from "../../table/Table.vue";
import LeagueModal from "./LeagueModal.vue";
import api from "../../../axios";

import { useStore } from "vuex";
const store = useStore();
const token = computed(() => store.state.token);

const leagues = ref([]);

const columns = [
              {
                name: "Name",
                field: "name",
                label: "Name"
              },
              {
                name: "Start Date",
                field: "start_date",
                label: "Start Date"
              },
              {
                name: "End Date",
                field: "end_date",
                label: "End Date"
              },
              {
                name: "Actions",
                field: "actions",
                label: "Actions"
              }
              
];

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
