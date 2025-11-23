<template>
  <div class="max-w-4xl mx-auto p-4 bg-gray-100 mt-12">
    <!-- Header -->
    <div class="text-center mb-6">
      <h2 class="text-3xl font-bold">
        {{ game?.team_a?.name || 'Team A' }} 
        vs 
        {{ game?.team_b?.name || 'Team B' }}
      </h2>
      <p class="text-gray-600">{{ formattedDate }} | {{ formattedTime }}</p>
      <span 
        class="mt-2 inline-block px-3 py-1 rounded-full font-semibold text-sm" 
        :class="statusColorClass"
      >
        {{ game?.status?.toUpperCase() }}
      </span>
    </div>

    <!-- Score -->
    <div class="bg-white shadow-lg rounded-xl p-6 flex items-center justify-between mb-6">
      <div class="w-1/3 text-center">
        <p class="text-xl font-bold text-blue-600">
          {{ game?.team_a?.name || 'Team A' }}
        </p>
      </div>
      <div class="w-1/3 text-center">
        <p class="text-6xl font-bold text-gray-800">
          {{ game?.team_a_score ?? 0 }} - {{ game?.team_b_score ?? 0 }}
        </p>
      </div>
      <div class="w-1/3 text-center">
        <p class="text-xl font-bold text-red-600">
          {{ game?.team_b?.name || 'Team B' }}
        </p>
      </div>
    </div>

    <!-- Team Stats -->
    <div class="bg-white shadow-md rounded-xl p-6 mb-6">
      <h3 class="text-xl font-bold mb-4 text-center">Team Statistics</h3>
      <div class="grid grid-cols-3 gap-6 text-center">
        <div>
          <div class="text-3xl font-bold text-blue-600">{{ game?.team_a_score }}</div>
          <div class="text-gray-600">Goals</div>
        </div>
        <div class="text-2xl font-semibold">VS</div>
        <div>
          <div class="text-3xl font-bold text-red-600">{{ game?.team_b_score }}</div>
          <div class="text-gray-600">Goals</div>
        </div>

        <div>
          <div class="text-2xl font-bold text-blue-600">{{ game?.team_a_fouls }}</div>
          <div class="text-gray-600">Fouls</div>
        </div>
        <div></div>
        <div>
          <div class="text-2xl font-bold text-red-600">{{ game?.team_b_fouls }}</div>
          <div class="text-gray-600">Fouls</div>
        </div>
      </div>
    </div>

    <!-- Players -->
    <div class="bg-white shadow-md rounded-xl p-6 mb-6">
      <h3 class="text-xl font-bold mb-4 text-center">Player Performance</h3>
      <div class="grid grid-cols-2 gap-8">
        <!-- Team A Players -->
        <div>
          <h4 class="font-bold text-blue-600 text-lg mb-3 text-center">
            {{ game?.team_a?.name || 'Team A' }} Players
          </h4>
          <ul class="space-y-2">
            <li v-for="player in game?.team_a_players" :key="player.id" 
                class="bg-blue-50 p-3 rounded-lg border border-blue-200">
              <strong>#{{ player.number }} {{ player.name }}</strong>
              <span class="text-sm text-gray-600 ml-2">
                ({{ player.position }})
              </span>
              <div class="text-sm mt-1">
                Goals: <strong>{{ player.goals }}</strong> | 
                Fouls: <strong>{{ player.fouls }}</strong>
              </div>
            </li>
            <li v-if="!game?.team_a_players?.length" class="text-gray-400 text-center">
              No players
            </li>
          </ul>
        </div>

        <!-- Team B Players -->
        <div>
          <h4 class="font-bold text-red-600 text-lg mb-3 text-center">
            {{ game?.team_b?.name || 'Team B' }} Players
          </h4>
          <ul class="space-y-2">
            <li v-for="player in game?.team_b_players" :key="player.id" 
                class="bg-red-50 p-3 rounded-lg border border-red-200">
              <strong>#{{ player.number }} {{ player.name }}</strong>
              <span class="text-sm text-gray-600 ml-2">
                ({{ player.position }})
              </span>
              <div class="text-sm mt-1">
                Goals: <strong>{{ player.goals }}</strong> | 
                Fouls: <strong>{{ player.fouls }}</strong>
              </div>
            </li>
            <li v-if="!game?.team_b_players?.length" class="text-gray-400 text-center">
              No players
            </li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Match Events -->
    <div class="bg-white shadow-md rounded-xl p-6">
      <h3 class="text-xl font-bold mb-4 text-center">Match Events</h3>
      <div class="max-h-96 overflow-y-auto">
        <div v-if="sortedEvents?.length>0" v-for="event in sortedEvents" :key="event.id" 
             class="flex items-center justify-between py-3 border-b last:border-0">
          <span class="font-mono font-bold text-sm">
            {{ event.minute ? event.minute + "'" : '-' }}
          </span>

          <div class="flex-1 text-center font-medium">
            <span v-if="event.type === 'goal'" class="text-green-600">
              Goal! {{ event.player?.name }} scores!
            </span>
            <span v-else class="text-orange-600">
              {{ event.type }}
            </span>
          </div>

          <span v-if="event.team_id === game?.team_a_id" 
                class="text-blue-600 font-bold text-sm">
            Team A
          </span>
          <span v-else class="text-red-600 font-bold text-sm">
            Team B
          </span>
        </div>

        <div v-if="!game?.events?.length" class="text-center text-gray-400 py-8">
          No events yet
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted, onBeforeUnmount,ref } from "vue";
import api from "@/axios";
import { useSocket } from "../../composables/useSocket";

const props = defineProps({
  id:{
    type:Number,
    retquired:true
  }
});

// These are the EXACT keys from your Laravel response
const game=ref(null);

const formattedDate = computed(() => {
  if (!game.value?.match_date) return "-";
  return new Date(game.value.match_date).toLocaleDateString("en-US", {
    weekday: "long",
    month: "short",
    day: "numeric",
  });
});

const formattedTime = computed(() => {
  return game.value?.start_time?.slice(0, 5) || "-";
});

const statusColorClass = computed(() => {
  const s = game.value?.status;
  return{
    'ongoing':'bg-green-200 text-green-800',
    'paused':'bg-yellow-200 text-yellow-800',
    'completed':'bg-blue-200 text-blue-800',
    'scheduled':'bg-gray-200 text-gray-800'
  }[s];
});

const socket=useSocket();

// Sort events by minute
const sortedEvents = computed(() => {
  if (!game.value?.events) return [];
  return [...game.value.events].sort((a, b) => {
    const ma = a.minute || 0;
    const mb = b.minute || 0;
    return ma - mb;
  });
});


async function fetchGame(){
  const res = await api.get(`/api/public/games/${props.id}`).then(res=>{
    game.value=res.data.data.game
  })
  console.log(game.value);
}

onMounted(async ()=>{
  await fetchGame();
  socket.listen(`game.${props.id}`, '.game.updated',(e)=>{

    console.log('Live update received:', e)
    game.value.events.push(e.newEvent);
    game.value.team_a_score=e.teamAScore
    game.value.team_b_score =e.teamBScore
    game.value.team_a_fouls =e.teamAFouls
    game.value.team_b_fouls =e.teamBFouls

    // emit('update','score',props.game.id,e);
  })
})
</script>