<template>
  <div
    v-if="show"
    class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50"
    @click.self="$emit('close')"
  >
    <div class="bg-white dark:bg-gray-800 w-full max-w-2xl rounded-lg shadow-2xl p-8 relative max-h-screen overflow-y-auto">
      <button
        @click="$emit('close')"
        class="absolute top-4 right-4 text-gray-500 hover:text-gray-900 text-3xl font-light"
      >
        ×
      </button>

      <h2 class="text-3xl font-bold mb-8 text-gray-900 dark:text-white">
        {{ isEdit ? "Edit Team" : "Create New Team" }}
      </h2>

      <form @submit.prevent="submitForm" class="space-y-6">
        <!-- Team Name -->
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Team Name
          </label>
          <input
            v-model="form.name"
            type="text"
            required
            placeholder="Enter team name"
            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
          />
        </div>

        <!-- League -->
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            League
          </label>
          <select
            v-model="form.league_id"
            required
            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
          >
            <option value="" disabled>Select a league</option>
            <option v-for="league in leagues" :key="league.id" :value="league.id">
              {{ league.name }}
            </option>
          </select>
        </div>

        <!-- Manager -->
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Team Manager
          </label>
          <select
            v-model="form.manager_id"
            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
          >
            <option :value="null">No Manager</option>
            <option v-for="user in users" :key="user.id" :value="user.id">
              {{ user.name }}
            </option>
          </select>
        </div>

        <!-- Players Multiselect -->
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Players
          </label>
          <Multiselect
            v-model="form.players"
            :options="players"
            mode="tags"
            :searchable="true"
            :close-on-select="false"
            :clear-on-select="false"
            placeholder="Search and select players"
            label="name"
            track-by="id"
            value-prop="id"
            :object="true"
            no-options-text="No players found"
            no-results-text="No players match your search"
            class="vueform-multiselect"
          />
        </div>

        <!-- Submit -->
        <div class="pt-6">
          <button
            type="submit"
            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-4 rounded-lg transition duration-200"
          >
            {{ isEdit ? "Update Team" : "Create Team" }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, onMounted } from "vue";
import api from "../../../axios";
import Multiselect from "@vueform/multiselect";
import "@vueform/multiselect/themes/default.css"; // This line is REQUIRED

const props = defineProps({
  show: Boolean,
  team: Object,
});

const emit = defineEmits(["close", "submit"]);

const form = ref({
  id: null,
  name: "",
  league_id: null,
  manager_id: null,
  players: [], 
});

const isEdit = ref(false);
const leagues = ref([]);
const users = ref([]);
const players = ref([]);

// Fetch all data
const fetchData = async () => {
  try {
    const [leaguesRes, managersRes, playersRes] = await Promise.all([
      api.get("/api/all-leagues"),
      api.get("/api/all-managers?role=team_manager"),
      api.get("/api/all-players"),
    ]);

    leagues.value = leaguesRes.data.data || [];
    users.value = managersRes.data.data || [];
    players.value = playersRes.data.data || [];
  } catch (err) {
    console.error("Failed to load data:", err);
  }
};

onMounted(fetchData);

// Watch for team prop changes (edit mode)
watch(
  () => props.team,
  (team) => {
    if (team) {
      // Edit mode
      form.value = {
        id: team.id,
        name: team.name || "",
        league_id: team.league_id || null,
        manager_id: team.manager?.id || null,
        players: team.players || [],
      };
      isEdit.value = true;
    } else {
      // Create mode - reset form
      form.value = {
        id: null,
        name: "",
        league_id: null,
        manager_id: null,
        players: [],
      };
      isEdit.value = false;
    }
  },
  { immediate: true }
);

// Reset when modal closes
watch(
  () => props.show,
  (visible) => {
    if (!visible) {
      // Optional: reset form when closed
      form.value.players = [];
    }
  }
);

const submitForm = () => {
  emit("submit", { ...form.value, isEdit: isEdit.value });
};
</script>

</style>