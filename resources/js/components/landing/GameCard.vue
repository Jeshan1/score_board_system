<template>
    <div class="bg-white shadow-md rounded-lg p-4 w-full max-w-md mx-auto my-4 border border-gray-200">
      <!-- Match Header -->
      <div class="flex justify-between items-center mb-2">
        <span class="text-gray-500 text-sm">{{ formattedDate }}</span>
        <span
          class="px-2 py-1 text-xs rounded-full"
          :class="statusColorClass"
        >
          {{ game.status.toUpperCase() }}
        </span>
      </div>
  
      <!-- Teams & Score -->
      <div class="flex justify-between items-center text-center my-4">
        <!-- Team A -->
        <div class="flex-1">
          <p class="font-semibold">{{ game.team_a.name || "Unknown" }}</p>
        </div>
  
        <!-- Score -->
        <div class="flex-1 text-2xl font-bold">
          {{ game.team_a_score }} - {{ game.team_b_score }}
        </div>
  
        <!-- Team B -->
        <div class="flex-1">
          <p class="font-semibold">{{ game.team_b.name || "Unknown" }}</p>
        </div>
      </div>
  
      <!-- Match Details -->
      <div class="text-sm text-gray-600">
        <p><strong>League:</strong> {{ game.league.name || "-" }}</p>
        <p><strong>Referee:</strong> {{ game.referee.name || "-" }}</p>
        <p><strong>Start Time:</strong> {{ game.start_time || "-" }}</p>
        <p><strong>End Time:</strong> {{ game.end_time || "-" }}</p>
      </div>
  
      <!-- Action buttons (optional) -->
      <div class="flex justify-end mt-3 gap-2">
        <button class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm" @click="$emit('view', game)">View</button>
      </div>
    </div>
  </template>
  
  <script setup>
  import { computed, onMounted,watch,ref } from "vue";
  import { useSocket } from "../../composables/useSocket";
  
  const props = defineProps({
    game: {
      type: Object,
      required: true,
    },
  });

  const socket=useSocket();
  const emit = defineEmits(["view","update"]);
  
  // Format the match date nicely
  const formattedDate = computed(() => {
    if (!props.game.match_date) return "-";
    const date = new Date(props.game.match_date);
    return date.toLocaleDateString(undefined, { weekday: 'short', month: 'short', day: 'numeric' });
  });
  
  // Status color class
  const statusColorClass = computed(() => {
    switch (props.game.status) {
      case "scheduled": return "bg-gray-200 text-gray-800";
      case "ongoing": return "bg-green-200 text-green-800";
      case "paused": return "bg-yellow-200 text-yellow-800";
      case "completed": return "bg-blue-200 text-blue-800";
      default: return "bg-gray-200 text-gray-800";
    }
  });

  const data=ref(null);

  onMounted( () => {
  // window.Echo.channel(`game.${props.game.id}`)
  //   .listen('game-status-updated', (e) => { // Must match broadcastAs()
  //     console.log('Live status update:', e);
  //     props.game.status = e.status;
  //     props.game.team_a_score = e.team_a_score;
  //     props.game.team_b_score = e.team_b_score;
  //   });
  socket.listen(`game.${props.game.id}`, '.game-status-updated',(e)=>{
    emit('update','status',props.game.id,e);
  })
  socket.listen(`game.${props.game.id}`, '.game.updated',(e)=>{
    emit('update','score',props.game.id,e);
  })

 
});

  </script>
  
  <style scoped>
  /* Add subtle hover effect */
  div:hover {
    transition: all 0.2s;
    transform: translateY(-2px);
  }
  </style>
  