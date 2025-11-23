<template>
    <Table
      :title="'Games'"
      :columns="columns"
      :data="games"
      @open-modal="openModal"
      @delete="deleteGame"
      @refresh-games="fetchGames"
      :permissions="{ create: true, edit: true, delete: true, updateEvents: true }"
    />
  
    <GameModal
      :show="showModal"
      :game="selectedGame"
      :teams="teams"
      :leagues="leagues"
      :referees="referees"
      @close="closeModal"
      @submit="handleSubmit"
    />
  </template>
  
  <script setup>
  import { ref, onMounted, computed } from "vue";
  import Table from "../../table/Table.vue";
  import GameModal from "./GameModal.vue";
  import api from "../../../axios";
  import { useStore } from "vuex";
  
  const store = useStore();
  const token = computed(() => store.state.token);
  
  const games = ref([]);
  const teams = ref([]);
  const leagues = ref([]);
  const referees = ref([]); // Users with role 'referee'
  
  const columns = [
    { name: "Team A", field: "team_a", label: "Team A" },
    { name: "Team B", field: "team_b", label: "Team B" },
    { name: "League", field: "league", label: "League" },
    { name: "Referee", field: "referee", label: "Referee" },
    { name: "Date", field: "match_date", label: "Date" },
    { name: "Start Time", field: "start_time", label: "Start Time" },
    { name: "End Time", field: "end_time", label: "End Time" },
    { name: "Status", field: "status", label: "Status" },
    { name: "Score", field: "score", label: "Score" },
    { name: "Actions", field: "actions", label: "Actions" },
  ];
  
  const showModal = ref(false);
  const selectedGame = ref(null);
  
  const fetchGames = async () => {
    try {
      const res = await api.get("/api/games", {
        headers: { Authorization: `Bearer ${token.value}` },
      });
  
      games.value = res.data.data.map((game) => ({
        ...game,
        team_a: game.team_a ? game.team_a.name : "-",
        team_b: game.team_b ? game.team_b.name : "-",
        league: game.league ? game.league.name : "-",
        referee: game.referee ? game.referee.name : "-",
        score: `${game.team_a_score} - ${game.team_b_score}`,
      }));
    } catch (err) {
      console.error("Error fetching games:", err);
    }
  };
  
  // Fetch teams, leagues, and referees for select inputs
  const fetchDependencies = async () => {
    try {
      const teamRes = await api.get("/api/teams", {
        headers: { Authorization: `Bearer ${token.value}` },
      });
      teams.value = teamRes.data.data;
  
      const leagueRes = await api.get("/api/leagues", {
        headers: { Authorization: `Bearer ${token.value}` },
      });
      leagues.value = leagueRes.data.data;
  
      const refereeRes = await api.get("/api/users-by-role?role=referee", {
        headers: { Authorization: `Bearer ${token.value}` },
      });
      referees.value = refereeRes.data.data;
    } catch (err) {
      console.error(err);
    }
  };
  
  const openModal = (game = null) => {
    selectedGame.value = game;
    showModal.value = true;
  };
  
  const closeModal = () => {
    selectedGame.value = null;
    showModal.value = false;
  };
  
  const handleSubmit = async (gameData) => {
    try {
      if (gameData.isEdit) {
        await api.put(`/api/games/${gameData.id}`, gameData, {
          headers: { Authorization: `Bearer ${token.value}` },
        });
      } else {
        await api.post("/api/games", gameData, {
          headers: { Authorization: `Bearer ${token.value}` },
        });
      }
      fetchGames();
    } catch (err) {
      console.error("Error saving game:", err);
    }
  };
  
  const deleteGame = async (id) => {
    try {
      await api.delete(`/api/games/${id}`, {
        headers: { Authorization: `Bearer ${token.value}` },
      });
      fetchGames();
    } catch (err) {
      console.error("Error deleting game:", err);
    }
  };
  
  onMounted(() => {
    fetchGames();
    fetchDependencies();
  });
  </script>
  