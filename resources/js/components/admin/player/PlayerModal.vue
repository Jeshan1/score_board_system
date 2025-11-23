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
          {{ isEdit ? "Edit Player" : "Create Player" }}
        </h2>
  
        <form @submit.prevent="submitForm" class="space-y-4">
          <div>
            <label class="block font-medium mb-1">Player Name</label>
            <input
              v-model="form.name"
              type="text"
              class="w-full px-4 py-2 border rounded-lg"
              required
            />
          </div>
  
          <div>
            <label class="block font-medium mb-1">Jersey Number</label>
            <input
              v-model="form.number"
              type="text"
              class="w-full px-4 py-2 border rounded-lg"
              required
            />
          </div>
  
          <div>
            <label class="block font-medium mb-1">Position</label>
            <input
              v-model="form.position"
              type="text"
              class="w-full px-4 py-2 border rounded-lg"
              required
            />
          </div>
  
          <div>
            <label class="block font-medium mb-1">Goals</label>
            <input
              v-model.number="form.goals"
              type="number"
              class="w-full px-4 py-2 border rounded-lg"
              required
            />
          </div>
  
          <div>
            <label class="block font-medium mb-1">Fouls</label>
            <input
              v-model.number="form.fouls"
              type="number"
              class="w-full px-4 py-2 border rounded-lg"
              required
            />
          </div>
  
          <button
            type="submit"
            class="w-full bg-blue-600 text-white py-2 rounded-lg"
          >
            {{ isEdit ? "Update Player" : "Create Player" }}
          </button>
        </form>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, watch } from "vue";
  
  const props = defineProps({
    show: Boolean,
    player: Object,
  });
  
  const emit = defineEmits(["close", "submit"]);
  
  const form = ref({
    id: null,
    name: "",
    number: "",
    position: "",
    goals: 0,
    fouls: 0,
  });
  
  const isEdit = ref(false);
  
  // Watch for player changes when opening modal
  watch(
    () => props.player,
    (val) => {
      if (val) {
        form.value = {
          id: val.id,
          name: val.name,
          number: val.number,
          position: val.position,
          goals: Number(val.goals),
          fouls: Number(val.fouls),
        };
        isEdit.value = true;
      } else {
        isEdit.value = false;
        form.value = {
          id: null,
          name: "",
          number: "",
          position: "",
          goals: 0,
          fouls: 0,
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
  