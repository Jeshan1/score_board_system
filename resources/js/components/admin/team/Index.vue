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
  import { ref, onMounted, computed } from "vue";
  import Table from "../../table/Table.vue";
  import TeamModal from "./TeamModal.vue";
  import api from "../../../axios";
  import { useStore } from "vuex";
  
  const store = useStore();
  const token = computed(() => store.state.token);
  
  const teams = ref([]);
  
  const columns = [
    { name: "Name", field: "name", label: "Team Name" },
    { name: "League", field: "league", label: "League" },
    { name: "Manager", field: "manager", label: "Manager" },
    { name: "Actions", field: "actions", label: "Actions" },
  ];
  
  const showModal = ref(false);
  const selectedTeam = ref(null);
  
  const fetchTeams = async () => {
    try {
      const response = await api.get("/api/teams", {
        headers: { Authorization: `Bearer ${token.value}` },
      });
  
      // Map teams to include league and manager names
      teams.value = response.data.data.map((team) => ({
        ...team,
        league: team.league ? team.league.name : "-",
        manager: team.manager ? { name: team.manager.name, id: team.manager.id } : { name: "-", id: null }, // Include manager's id and name
      }));

      console.log("teams", teams.value);
    } catch (error) {
      console.error("Error fetching teams:", error);
    }
  };
  
  const openModal = (team = null) => {
    selectedTeam.value = team;
    showModal.value = true;
  };
  
  const closeModal = () => {
    selectedTeam.value = null;
    showModal.value = false;
  };
  
  const handleSubmit = async (teamData) => {
    try {
      if (teamData.isEdit) {
        await api.put(`/api/team/update/${teamData.id}`, teamData, {
          headers: { Authorization: `Bearer ${token.value}` },
        });
      } else {
        await api.post("/api/team/create", teamData, {
          headers: { Authorization: `Bearer ${token.value}` },
        });
      }
      fetchTeams(); // reload after submit
    } catch (error) {
      console.error("Error saving team:", error);
    }
  };
  
  const deleteTeam = async (id) => {
    try {
      await api.delete(`/api/teams/${id}`, {
        headers: { Authorization: `Bearer ${token.value}` },
      });
      fetchTeams();
    } catch (error) {
      console.error("Error deleting team:", error);
    }
  };
  
  onMounted(fetchTeams);
  </script>
  