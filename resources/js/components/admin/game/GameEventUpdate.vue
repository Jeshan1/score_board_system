<template>
    <div class="scrollable-container">
      <!-- Match Header -->
      <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-2xl rounded-2xl p-8 mb-10 text-center">
        <h1 class="text-4xl font-bold">
          {{ teamA?.name || 'Team A' }} vs {{ teamB?.name || 'Team B' }}
        </h1>
        <div class="text-7xl font-extrabold mt-4 tracking-wider">
          {{ game.team_a_score }} - {{ game.team_b_score }}
        </div>
        <p class="text-xl mt-3 opacity-90">
          {{ formatDate(game.match_date) }} • {{ game.start_time?.slice(0, 5) }}
        </p>
      </div>
  
      <!-- ADD NEW EVENT FORM -->
      <div class="bg-white rounded-xl shadow-xl p-8 mb-10 border border-gray-200">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-3">
          Add New Event
        </h2>
  
        <form @submit.prevent="saveEvent" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
          <!-- Event Type -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
            <select v-model="newEvent.type" required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-indigo-500">
              <option value="goal">Goal</option>
              <option value="foul">Foul</option>
            </select>
          </div>
  
          <!-- Player -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Player</label>
            <select v-model="newEvent.player_id" required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-indigo-500">
              <option :value="null" disabled>Select Player</option>
              <optgroup :label="teamA?.name">
                <option v-for="p in teamAPlayers" :key="p.id" :value="p.id">
                  #{{ p.number }} {{ p.name }}
                </option>
              </optgroup>
              <optgroup :label="teamB?.name">
                <option v-for="p in teamBPlayers" :key="p.id" :value="p.id">
                  #{{ p.number }} {{ p.name }}
                </option>
              </optgroup>
            </select>
          </div>
  
          <!-- Minute -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Minute</label>
            <input
              v-model.number="newEvent.minute"
              type="number"
              min="1"
              max="120"
              placeholder="e.g. 45"
              class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-indigo-500"
            />
          </div>
  
          <!-- Foul Type (only if foul) -->
          <div v-if="newEvent.type === 'foul'">
            <label class="block text-sm font-medium text-gray-700 mb-1">Card Type</label>
            <select v-model="newEvent.foul_type" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-indigo-500">
              <option value="yellow">Yellow Card</option>
              <option value="red">Red Card</option>
              <option value="technical">Technical Foul</option>
            </select>
          </div>
  
          <!-- Victim Player (only if foul) -->
          <div v-if="newEvent.type === 'foul'">
            <label class="block text-sm font-medium text-gray-700 mb-1">Victim Player (optional)</label>
            <select v-model="newEvent.victim_player_id" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-indigo-500">
              <option :value="null">No victim</option>
              <optgroup :label="teamA?.name">
                <option v-for="p in teamAPlayers" :key="p.id" :value="p.id">#{{ p.number }} {{ p.name }}</option>
              </optgroup>
              <optgroup :label="teamB?.name">
                <option v-for="p in teamBPlayers" :key="p.id" :value="p.id">#{{ p.number }} {{ p.name }}</option>
              </optgroup>
            </select>
          </div>
  
          <!-- Submit Button -->
          <div class="flex items-end">
            <button
              type="submit"
              class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 rounded-lg shadow-lg transition transform hover:scale-105"
            >
              Add Event
            </button>
          </div>
        </form>
  
        <!-- Reset Form Button -->
        <button
          @click="resetForm"
          class="mt-4 text-sm text-gray-500 hover:text-gray-700 underline"
        >
          Clear form
        </button>
      </div>
  
      <!-- EVENTS TABLE -->
      <div class="bg-white rounded-xl shadow-xl overflow-hidden mb-10">
        <h2 class="text-2xl font-bold text-gray-800 p-6 bg-gray-50 border-b">
          Match Events ({{ events.length }})
        </h2>
  
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-100 text-gray-700 text-sm uppercase tracking-wider">
              <tr>
                <th class="px-6 py-4 text-left">Minute</th>
                <th class="px-6 py-4 text-left">Team</th>
                <th class="px-6 py-4 text-left">Player</th>
                <th class="px-6 py-4 text-center">Event</th>
                <th class="px-6 py-4 text-left">Details</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              <tr v-for="event in sortedEvents" :key="event.id" class="hover:bg-gray-50 transition">
                <td class="px-6 py-5 font-bold text-lg">
                  {{ event.minute || '?' }}'
                </td>
                <td class="px-6 py-5">
                  <span class="font-semibold" :class="event.team_id == game.team_a_id ? 'text-blue-600' : 'text-red-600'">
                    {{ event.team_id == game.team_a_id ? teamA?.name : teamB?.name }}
                  </span>
                </td>
                <td class="px-6 py-5">
                  #{{ event.player?.number }} {{ event.player?.name || 'Unknown' }}
                </td>
                <td class="px-6 py-5 text-center">
                  <span class="px-4 py-2 rounded-full text-white font-bold text-sm"
                        :class="event.type === 'goal' ? 'bg-green-600' : 'bg-yellow-600'">
                    {{ event.type === 'goal' ? 'Goal' : 'Foul' }}
                  </span>
                </td>
                <td class="px-6 py-5 text-sm">
                  <span v-if="event.type === 'foul'">
                    {{ event.foul_type }} card
                    <span v-if="event.victim_player_id">
                      → #{{ event.victimPlayer?.number }} {{ event.victimPlayer?.name }}
                    </span>
                  </span>
                </td>
              </tr>
              <tr v-if="events.length === 0">
                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                  No events yet. Add the first one above!
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted, computed } from 'vue'
  import axios from 'axios'
  import { useRoute } from 'vue-router'
  import { useStore } from 'vuex'
  
  const props = defineProps({ id: Number })
  const route = useRoute()
  const store = useStore()
  const token = computed(() => store.state.token)
  
  // Data
  const game = ref({})
  const events = ref([])
  const teamAPlayers = ref([])
  const teamBPlayers = ref([])
  const teamA = ref({})
  const teamB = ref({})
  
  // New Event Form
  const newEvent = ref({
    type: 'goal',
    player_id: null,
    minute: null,
    foul_type: null,
    victim_player_id: null,
    match_id: null
  })
  
  const fetchMatch = async () => {
    if (!token.value) return
  
    try {
      const matchId = props.id || route.params.id
      const res = await axios.get(`/api/matches/${matchId}/events`, {
        headers: { Authorization: `Bearer ${token.value}` }
      })
  
      game.value = res.data.game
      events.value = res.data.events || []
      teamAPlayers.value = res.data.teamAPlayers || []
      teamBPlayers.value = res.data.teamBPlayers || []
      teamA.value = res.data.teamA
      teamB.value = res.data.teamB
      newEvent.value.match_id = game.value.id
  
    } catch (err) {
      console.error(err)
      alert('Failed to load match')
    }
  }
  
  const saveEvent = async () => {
    if (!newEvent.value.player_id) {
      alert('Please select a player')
      return
    }
  
    try {
      // Auto detect team_id
      const player = [...teamAPlayers.value, ...teamBPlayers.value].find(p => p.id === newEvent.value.player_id)
      const payload = { ...newEvent.value, team_id: player.team_id }
  
      const res = await axios.post(`/api/match/${game.value.id}/events`, payload, {
        headers: { Authorization: `Bearer ${token.value}` }
      })
  
      // Add new event to list
      events.value.push(res.data.event)
      game.value = res.data.game
  
      alert('Event added!')
      resetForm()
  
    } catch (err) {
      alert('Error: ' + (err.response?.data?.message || 'Failed to save'))
    }
  }
  
  const resetForm = () => {
    newEvent.value = {
      type: 'goal',
      player_id: null,
      minute: null,
      foul_type: null,
      victim_player_id: null,
      match_id: game.value.id
    }
  }
  
  const sortedEvents = computed(() => {
    return [...events.value].sort((a, b) => (a.minute || 0) - (b.minute || 0))
  })
  
  const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-GB', { day: 'numeric', month: 'long', year: 'numeric' })
  }
  
  onMounted(fetchMatch)
  </script>
  
  <style scoped>
  .scrollable-container {
    max-height: 100vh; /* Full viewport height */
    overflow-y: auto; /* Scroll vertically */
    padding: 1.5rem;
    -webkit-overflow-scrolling: touch; /* Smooth scrolling on mobile */
  }
  
  /* Ensure table stays responsive */
  table {
    min-width: 100%;
  }
  
  /* Fix for sticky headers or form if needed */
  .bg-gradient-to-r,
  .bg-white {
    width: 100%;
  }
  
  /* Smooth focus styles */
  input:focus,
  select:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.3);
  }
  
  /* Hide scrollbars but keep scrolling */
  .scrollable-container::-webkit-scrollbar {
    display: none;
  }
  .scrollable-container {
    -ms-overflow-style: none; /* IE and Edge */
    scrollbar-width: none; /* Firefox */
  }
  </style>