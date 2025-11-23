<template>
  <div v-if="show" class="fixed inset-0 bg-white/10 backdrop-blur-sm flex items-center justify-center z-50">
    <div class="bg-white w-full max-w-md rounded-lg shadow-lg p-6 relative">
      <!-- Close button -->
      <button
        @click="$emit('close')"
        class="absolute top-2 right-2 text-gray-500 hover:text-gray-700"
      >
        ✕
      </button>

      <h2 class="text-2xl font-semibold mb-6 text-gray-800">
        {{ isEdit ? 'Edit League' : 'Create League' }}
      </h2>

      <form @submit.prevent="submitForm" class="space-y-4">
        <!-- League Name -->
        <div>
          <label class="block text-gray-700 font-medium mb-1">League Name</label>
          <input
            type="text"
            v-model="form.name"
            placeholder="Enter league name"
            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
            required
          />
        </div>

        <!-- Start Date -->
        <div>
          <label class="block text-gray-700 font-medium mb-1">Start Date</label>
          <input
            type="date"
            v-model="form.start_date"
            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
            required
          />
        </div>

        <!-- End Date -->
        <div>
          <label class="block text-gray-700 font-medium mb-1">End Date</label>
          <input
            type="date"
            v-model="form.end_date"
            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
            required
          />
        </div>

        <!-- Submit Button -->
        <div>
          <button
            type="submit"
            class="w-full bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors"
          >
            {{ isEdit ? 'Update League' : 'Create League' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";

const props = defineProps({
  show: { type: Boolean, required: true },
  league: { type: Object, default: null }
});

const emit = defineEmits(["close", "submit"]);

const form = ref({
  name: "",
  start_date: "",
  end_date: ""
});

const isEdit = ref(false);

onMounted(() => {
  console.log("league", props.league);
  if (props.league) {
    form.value = { ...props.league };
    isEdit.value = true;
  }
});

const submitForm = () => {
  emit("submit", { ...form.value, isEdit: isEdit.value });
  emit("close");
};
</script>
