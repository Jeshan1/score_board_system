<template>
  <Table
    :title="'Teams'"
    :columns="columns"
    :data="teams"
    @open-modal="openModal"
    @delete="deleteTeam"
  />

  <TeamModal
    :show="showModal"
    :team="selectedTeam"
    @close="closeModal"
    @submit="handleSubmit"
  />
</template>

<script setup>
import { ref, onMounted } from "vue";
import Table from "../../table/Table.vue";
import TeamModal from "./TeamModal.vue";
import api from "../../../axios";
import { useStore } from "vuex";

const store = useStore();
const token = () => store.state.token;

const teams = ref([]);
const showModal = ref(false);
const selectedTeam = ref(null);

const columns = [
  { name: "Name", field: "name", label: "Team Name" },
  { name: "League", field: "league", label: "League" },
  { name: "Manager", field: "manager", label: "Manager" },
  { name: "Actions", field: "actions", label: "Actions" },
];

// Critical Fix: Include players array in team data
const fetchTeams = async () => {
  try {
    const response = await api.get("/api/teams", {
      headers: { Authorization: `Bearer ${token()}` },
    });

    console.log(response.data.data);
    teams.value = response.data.data.map((team) => ({
      ...team,
      league: team.league?.name || "-",
      manager: team.manager ? { name: team.manager.name, id: team.manager.id } : { name: "-", id: null },
      players: team.players || [], // This was missing before!
    }));
    console.log("teams", teams.value);
  } catch (error) {
    console.error("Error fetching teams:", error);
  }
};

const openModal = (team = null) => {
  selectedTeam.value = team ? { ...team } : null; // Deep clone to avoid mutation
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
  selectedTeam.value = null;
};

const handleSubmit = async (teamData) => {
  try {
    if (teamData.isEdit) {
      await api.put(`/api/team/update/${teamData.id}`, teamData, {
        headers: { Authorization: `Bearer ${token()}` },
      });
    } else {
      await api.post("/api/team/create", teamData, {
        headers: { Authorization: `Bearer ${token()}` },
      });
    }
    await fetchTeams(); // Refresh list
    closeModal();
  } catch (error) {
    console.error("Error saving team:", error.response?.data || error);
  }
};

const deleteTeam = async (id) => {
  if (!confirm("Are you sure you want to delete this team?")) return;

  try {
    await api.delete(`/api/teams/${id}`, {
      headers: { Authorization: `Bearer ${token()}` },
    });
    fetchTeams();
  } catch (error) {
    console.error("Error deleting team:", error);
  }
};

onMounted(fetchTeams);
</script>