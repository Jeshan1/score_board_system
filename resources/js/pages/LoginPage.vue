<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-md">

      <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">
        Login to Your Account
      </h2>

      <!-- Email -->
      <input
        v-model="email"
        type="email"
        placeholder="Enter email"
        class="w-full mb-4 px-4 py-2 border rounded-lg"
      />

      <!-- Password -->
      <input
        v-model="password"
        type="password"
        placeholder="Enter password"
        class="w-full mb-4 px-4 py-2 border rounded-lg"
      />

      <!-- Login Button -->
      <button
        @click="submitLogin"
        class="w-full bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700"
      >
        Login
      </button>

      <!-- Error -->
      <p v-if="error" class="mt-4 text-red-600">{{ error }}</p>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from "vue";
import { useStore } from "vuex";
import { useRouter } from "vue-router";

const store = useStore();
const router = useRouter();

const email = ref("");
const password = ref("");

const error = computed(() => store.state.error);

async function submitLogin() {
  console.log("Login clicked!"); 

  await store.dispatch("login", { email: email.value, password: password.value });

  if (store.state.error) return;

  router.push("/admin");
}
</script>
