<template>
    <div v-if="show" class="fixed inset-0 bg-white/10 backdrop-blur-sm flex items-center justify-center z-50">
      <div class="bg-white w-full max-w-xl rounded-lg shadow-lg p-6 relative">
        <button @click="$emit('close')" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">✕</button>
  
        <h2 class="text-2xl font-semibold mb-6">{{ isEdit ? "Edit Game" : "Create Game" }}</h2>
  
        <form @submit.prevent="submitForm" class="space-y-4">
          <div>
            <label>Team A</label>
            <select v-model="form.team_a_id" required class="w-full border px-3 py-2 rounded">
              <option v-for="team in teams" :key="team.id" :value="team.id">{{ team.name }}</option>
            </select>
          </div>
  
          <div>
            <label>Team B</label>
            <select v-model="form.team_b_id" required class="w-full border px-3 py-2 rounded">
              <option v-for="team in teams" :key="team.id" :value="team.id">{{ team.name }}</option>
            </select>
          </div>
  
          <div>
            <label>League</label>
            <select v-model="form.league_id" required class="w-full border px-3 py-2 rounded">
              <option v-for="league in leagues" :key="league.id" :value="league.id">{{ league.name }}</option>
            </select>
          </div>
  
          <div>
            <label>Referee</label>
            <select v-model="form.referee_id" class="w-full border px-3 py-2 rounded">
              <option value="">Select Referee</option>
              <option v-for="ref in referees" :key="ref.id" :value="ref.id">{{ ref.name }}</option>
            </select>
          </div>
  
          <div>
            <label>Match Date</label>
            <input type="date" v-model="form.match_date" required class="w-full border px-3 py-2 rounded" />
          </div>
  
          <div>
            <label>Start Time</label>
            <input type="time" v-model="form.start_time" class="w-full border px-3 py-2 rounded" />
          </div>
  
          <div>
            <label>End Time</label>
            <input type="time" v-model="form.end_time" class="w-full border px-3 py-2 rounded" />
          </div>
  
          <div>
            <label>Status</label>
            <select v-model="form.status" class="w-full border px-3 py-2 rounded">
              <option value="scheduled">Scheduled</option>
              <option value="ongoing">Ongoing</option>
              <option value="paused">Paused</option>
              <option value="completed">Completed</option>
            </select>
          </div>
  
          <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg">{{ isEdit ? "Update Game" : "Create Game" }}</button>
        </form>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, watch } from "vue";
  
  const props = defineProps({
    show: Boolean,
    game: Object,
    teams: Array,
    leagues: Array,
    referees: Array,
  });
  
  const emit = defineEmits(["close", "submit"]);
  
  const form = ref({
    id: null,
    team_a_id: null,
    team_b_id: null,
    league_id: null,
    referee_id: null,
    match_date: "",
    start_time: "",
    end_time: "",
    status: "scheduled",
  });
  
  const isEdit = ref(false);
  
  watch(
    () => props.game,
    (val) => {
      if (val) {
        form.value = {
          id: val.id,
          team_a_id: val.team_a_id,
          team_b_id: val.team_b_id,
          league_id: val.league_id,
          referee_id: val.referee_id,
          match_date: val.match_date,
          start_time: val.start_time,
          end_time: val.end_time,
          status: val.status,
        };
        isEdit.value = true;
      } else {
        isEdit.value = false;
        form.value = {
          id: null,
          team_a_id: null,
          team_b_id: null,
          league_id: null,
          referee_id: null,
          match_date: "",
          start_time: "",
          end_time: "",
          status: "scheduled",
        };
      }
    },
    { immediate: true }
  );
  
  const submitForm = () => {
    emit("submit", { ...form.value, isEdit: isEdit.value });
    emit("close");
  };
  </script>
  