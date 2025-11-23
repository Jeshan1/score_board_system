<template>
    <div class="max-w-xl mx-auto my-8 p-6 bg-white shadow-lg rounded-lg">
      <h2 class="text-2xl font-semibold text-center mb-6">Create User</h2>
  
      <!-- Success or Error messages -->
      <div v-if="message" :class="message.type" class="mb-4 p-4 rounded">
        {{ message.text }}
      </div>
  
      <!-- User Creation Form -->
      <form @submit.prevent="handleSubmit">
        <div class="mb-4">
          <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
          <input
            type="text"
            id="name"
            v-model="form.name"
            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
            placeholder="Enter name"
            required
          />
        </div>
  
        <div class="mb-4">
          <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
          <input
            type="email"
            id="email"
            v-model="form.email"
            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
            placeholder="Enter email"
            required
          />
        </div>
  
        <div class="mb-4">
          <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
          <input
            type="text"
            id="password"
            v-model="form.password"
            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
            placeholder="Enter password"
            required
          />
        </div>
  
        <div class="mb-4">
          <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
          <input
            type="text"
            id="password_confirmation"
            v-model="form.password_confirmation"
            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
            placeholder="Confirm password"
            required
          />
        </div>
  
        <!-- Role Dropdown -->
        <div class="mb-4">
          <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
          <select
            id="role"
            v-model="form.role"
            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
            required
          >
          <option value="" disabled selected>Select Role</option>
            <option v-for="role in roles" :key="role" :value="role">{{ role }}</option>
          </select>
        </div>
  
        <button
          type="submit"
          class="w-full py-2 px-4 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
        >
          Save
        </button>
      </form>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from "vue";
  import axios from "axios";
  import { useRouter } from "vue-router";
  
  // Reactive form data
  const form = ref({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
    role: "", // Add role to form data
  });
  
  // Array to hold available roles
  const roles = ref([]);
  
  // Message for success or error
  const message = ref(null);
  
  // Get router instance for redirection
  const router = useRouter();
  
  // Fetch roles from the backend
  const fetchRoles = async () => {
    try {
      const response = await axios.get("/api/get-roles"); 
      console.log("response",response.data.data);
      roles.value = response.data.data;
    } catch (error) {
      message.value = {
        text: "Failed to load roles. Please try again.",
        type: "bg-red-100 text-red-800",
      };
      console.error("Error fetching roles:", error);
    }
  };
  
  // Call fetchRoles when the component is mounted
  onMounted(() => {
    fetchRoles();
  });
  
  // Handle form submission
  const handleSubmit = async () => {
    // Basic validation
    if (form.value.password.trim() != form.value.password_confirmation.trim()) {
      message.value = {
        text: "Passwords do not match!",
        type: "bg-red-100 text-red-800",
      };
      return;
    }
  
    try {
      // Send data to the backend via axios (replace with your API endpoint)
      const response = await axios.post("/api/user/create", {
        name: form.value.name,
        email: form.value.email,
        password: form.value.password.trim(),
        role: form.value.role, // Include role in the request data
      });
  
      // On success, show success message and reset the form
      message.value = {
        text: "User created successfully!",
        type: "bg-green-100 text-green-800",
      };
  
      // Optionally redirect to another page after success
      router.push("/users"); // Redirect to a users list page (or another route)
    } catch (error) {
      // Handle errors (e.g., validation errors from the backend)
      if (error.response && error.response.data) {
        message.value = {
          text: error.response.data.message || "Something went wrong.",
          type: "bg-red-100 text-red-800",
        };
      } else {
        message.value = {
          text: "Server error. Please try again later.",
          type: "bg-red-100 text-red-800",
        };
      }
    }
  };
  </script>
  
  <style scoped>
  /* You can add any custom styles here if needed */
  </style>
  