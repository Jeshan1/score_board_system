<template>
  <div
    v-if="show"
    class="fixed inset-0 bg-white/10 backdrop-blur-sm flex items-center justify-center z-50"
  >
    <div class="bg-white w-full max-w-xl rounded-lg shadow-lg p-6 relative">
      <button
        @click="$emit('close')"
        class="absolute top-2 right-2 text-gray-500 hover:text-gray-700"
      >
        ✕
      </button>

      <h2 class="text-2xl font-semibold mb-6">
        {{ isEdit ? "Edit Team" : "Create Team" }}
      </h2>

      <form @submit.prevent="submitForm" class="space-y-4">
        <!-- Team Name -->
        <div>
          <label class="block font-medium mb-1">Team Name</label>
          <input
            v-model="form.name"
            type="text"
            class="w-full px-4 py-2 border rounded-lg"
            required
          />
        </div>

        <!-- League -->
        <div>
          <label class="block font-medium mb-1">League</label>
          <select
            v-model="form.league_id"
            class="w-full px-4 py-2 border rounded-lg"
            required
          >
            <option value="" disabled>Select League</option>
            <option v-for="league in leagues" :key="league.id" :value="league.id">
              {{ league.name }}
            </option>
          </select>
        </div>

        <!-- Team Manager -->
        <div>
          <label class="block font-medium mb-1">Team Manager</label>
          <select
            v-model="form.manager_id"
            class="w-full px-4 py-2 border rounded-lg"
            required
          >
            <option value="" disabled>Select Manager</option>
            <option v-for="user in users" :key="user.id" :value="user.id">
              {{ user.name }}
            </option>
          </select>
        </div>

        <!-- Players Multi-Select -->
        <div>
          <label class="block font-medium mb-1">Players</label>
          <div class="relative">
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Search players"
              class="w-full px-4 py-2 border rounded-lg mb-2"
              @focus="isDropdownVisible = true"
              @blur="hideDropdown"
            />

            <!-- Dropdown -->
            <ul
              v-if="isDropdownVisible && filteredPlayers.length"
              class="absolute w-full bg-white border rounded-lg max-h-40 overflow-y-auto z-10 mt-1"
            >
              <li
                v-for="player in filteredPlayers"
                :key="player.id"
                class="px-4 py-2 hover:bg-gray-200 cursor-pointer flex justify-between"
                @click="togglePlayerSelection(player)"
              >
                <span>{{ player.name }}</span>
                <span v-if="form.players.includes(player.id)" class="text-green-500">✔</span>
              </li>
            </ul>

            <!-- No results -->
            <div
              v-if="!filteredPlayers.length && isDropdownVisible"
              class="absolute w-full bg-white border rounded-lg mt-1 p-2 text-center text-gray-500"
            >
              No players found
            </div>
          </div>

          <!-- Selected Players Pills -->
          <div v-if="form.players.length" class="mt-2">
            <strong>Selected Players:</strong>
            <div class="flex flex-wrap gap-2 mt-1">
              <span
                v-for="playerId in form.players"
                :key="playerId"
                class="bg-blue-600 text-white px-3 py-1 rounded-full text-sm flex items-center gap-1"
              >
                {{ getPlayerName(playerId) }}
                <button
                  type="button"
                  @click="removePlayer(playerId)"
                  class="text-red-500 font-bold"
                >
                  ✕
                </button>
              </span>
            </div>
          </div>
        </div>

        <!-- Submit -->
        <button
          type="submit"
          class="w-full bg-blue-600 text-white py-2 rounded-lg"
        >
          {{ isEdit ? "Update Team" : "Create Team" }}
        </button>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from "vue";
import api from "../../../axios";

// Props and Emit
const props = defineProps({
  show: Boolean,
  team: Object,
});
const emit = defineEmits(["close", "submit"]);

// Form Model
const form = ref({
  id: null,
  name: "",
  league_id: null,
  manager_id: null,
  logo: "",
  players: [], // Array of player IDs
});
const isEdit = ref(false);

// Data Lists
const leagues = ref([]);
const users = ref([]);
const players = ref([]);

// Search Dropdown
const searchQuery = ref("");
const isDropdownVisible = ref(false);

// Filtered players
const filteredPlayers = computed(() => {
  let filtered = players.value.filter(p =>
    p.name.toLowerCase().includes(searchQuery.value.toLowerCase())
  );

  // Put selected players at the top
  const selected = filtered.filter(p => form.value.players.includes(p.id));
  const notSelected = filtered.filter(p => !form.value.players.includes(p.id));
  return [...selected, ...notSelected];
});

// Helper to get player name
const getPlayerName = (id) => {
  const player = players.value.find(p => p.id === id);
  console.log("player", players.value);
  return player ? player.name : "Unknown Player";
};

// Toggle selection
const togglePlayerSelection = (player) => {
  if (form.value.players.includes(player.id)) {
    form.value.players = form.value.players.filter(id => id !== player.id);
  } else {
    form.value.players.push(player.id);
  }
  searchQuery.value = "";
};

// Remove player
const removePlayer = (id) => {
  form.value.players = form.value.players.filter(pid => pid !== id);
};

// Hide dropdown
const hideDropdown = () => {
  setTimeout(() => isDropdownVisible.value = false, 200);
};

// Fetch leagues, managers, players
const fetchData = async () => {
  try {
    const leagueRes = await api.get("/api/all-leagues");
    leagues.value = leagueRes.data.data;

    const userRes = await api.get("/api/all-managers?role=team_manager");
    users.value = userRes.data.data;

    const playerRes = await api.get("/api/all-players");
    players.value = playerRes.data.data;
    console.log("players", players.value);
  } catch (err) {
    console.error(err);
  }
};

onMounted(fetchData);

// Watch for edit mode
watch(() => props.team, (val) => {
  if (val) {
    form.value = {
      id: val.id,
      name: val.name,
      league_id: val.league_id,
      manager_id: val.manager ? val.manager.id : null,
      logo: val.logo || "",
      players: val.players ? val.players.map(p => p.id) : [],
    };
    isEdit.value = true;
  } else {
    form.value = {
      id: null,
      name: "",
      league_id: null,
      manager_id: null,
      logo: "",
      players: [],
    };
    isEdit.value = false;
  }
}, { immediate: true });

// Submit form
const submitForm = () => {
  emit("submit", { ...form.value, isEdit: isEdit.value });
  emit("close");
};
</script>

<style scoped>
input[type="text"] {
  background-color: #f9f9f9;
}

ul {
  max-height: 200px;
  overflow-y: auto;
}

li {
  cursor: pointer;
}

li:hover {
  background-color: #f0f0f0;
}

button {
  cursor: pointer;
}
</style>
