<template>
    <Table
      :title="'Players'"
      :columns="columns"
      :data="players"
      @open-modal="openModal"
      @delete="deletePlayer"
      :permissions="{ create: true, edit: true, delete: true, updateEvents: false}"
    />
  
    <PlayerModal
      :show="showModal"
      :player="selectedPlayer"
      @close="closeModal"
      @submit="handleSubmit"
    />
  </template>
  
  <script setup>
  import { ref, onMounted, computed } from "vue";
  import Table from "../../table/Table.vue";
  import PlayerModal from "./PlayerModal.vue";
  import api from "../../../axios";
  
  import { useStore } from "vuex";
  const store = useStore();
  const token = computed(() => store.state.token);
  
  const players = ref([]);
  
  const columns = [
    { name: "Name", field: "name", label: "Name" },
    { name: "Jersy Number", field: "number", label: "Number" },
    { name: "Position", field: "position", label: "Position" },
    { name: "Goals", field: "goals", label: "Goals" },
    { name: "Fouls", field: "fouls", label: "Fouls" },
    { name: "Actions", field: "actions", label: "Actions" },
  ];
  
  const showModal = ref(false);
  const selectedPlayer = ref(null);
  
  const fetchPlayers = async () => {
    try {
      const response = await api.get("/api/players", {
        headers: { Authorization: `Bearer ${token.value}` },
      });
  
      players.value = response.data.data;
    } catch (error) {
      console.error("Error fetching players:", error);
    }
  };
  
  const openModal = (player = null) => {
    selectedPlayer.value = player;
    showModal.value = true;
  };
  
  const closeModal = () => {
    selectedPlayer.value = null;
    showModal.value = false;
  };
  
  const handleSubmit = async (playerData) => {
    console.log("playerData", playerData);
    if (playerData.isEdit) {
      await api.put(`/api/players/${playerData.id}`, playerData, {
        headers: { Authorization: `Bearer ${token.value}` },
      });
    } else {
      await api.post("/api/players", playerData, {
        headers: { Authorization: `Bearer ${token.value}` },
      });
    }
  
    fetchPlayers(); // reload immediately
  };
  
  const deletePlayer = async (id) => {
    await api.delete(`/api/players/${id}`, {
      headers: { Authorization: `Bearer ${token.value}` },
    });
  
    fetchPlayers();
  };
  
  onMounted(fetchPlayers);
  </script>
  