<template>
  <div class="p-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    <GameCard
      v-for="game in games"
      :key="game.id"
      :game="game"
      @view="viewGame"
      :teamAStats="teamAStats"
      :teamBStats="teamBStats"
      @update="updateGameStats"
    />
  </div>

  <Teleport to="body">
    <div 
      v-if="showModal"
      class="fixed inset-0 backdrop-blur-md bg-white/30 flex justify-center items-center z-50"
      @click.self="closeModal" 
    >
      <div class="bg-white w-full max-w-4xl rounded-lg shadow-lg p-4 relative">
        <button
          @click="closeModal"
          class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 font-bold text-lg"
        >
          ×
        </button>

        <div v-if="loadingDetails" class="flex justify-center items-center py-4">
          <span class="text-xl">Loading...</span>
          <!-- You can also add a spinner here -->
        </div>

        <GameDetails :id="gameId" @update="updateGameStats"/>
      </div>
    </div>
  </Teleport>
</template>

<script setup>
import { ref, onMounted } from "vue";
import api from "@/axios";
import GameCard from "./GameCard.vue";
import GameDetails from "./GameDetail.vue"; // Ensure this is imported

const games = ref([]);
const teamAStats = ref({});
const teamBStats = ref({});
const showModal = ref(false);
const loadingDetails = ref(false);
const gameId = ref(null);

// Fetch games list
const fetchGames = async () => {
  try {
    const res = await api.get("/api/public/games");
    games.value = res.data.data;
  } catch (err) {
    console.error("Error fetching games:", err);
  }
};

onMounted(async ()=>{
  await fetchGames();
 
});

// Fetch game details when clicking 'view'
const viewGame = async (game) => {
  try {
    gameId.value = game.id;
    loadingDetails.value = true;
    showModal.value = true;
  } catch (err) {
    console.error("Error fetching game details:", err);
  } finally {
    loadingDetails.value = false;
  }
};

// Close modal
const closeModal = () => {
  showModal.value = false;
  // selectedGame.value = null;
};

function updateGameStats(type,id,e){
  const temp=games.value.map(game=>{
    if(game.id==id){
      if(type==='status'){
        game.status=e.status
      }else{
        game.team_a_score=e.teamAScore
        game.team_b_score =e.teamBScore
      }
    }
    return game
  })
  console.log(e);

  games.value=temp
}
</script>
