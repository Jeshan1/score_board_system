
<template>

    <Table
      :title="'User Management'"
      :columns="columns"
      :data="users"
      @open-modal=""
      @delete=""
    >
    <!-- Pass the Create User button into the header slot -->
        <template v-slot:header>
        <router-link :to="{ name: 'users.create' }">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Create User
            </button>
        </router-link>
        </template>
    </Table>
  </template>
  
  <script setup>
  import { ref, computed, onMounted, watch } from "vue";
  import Table from "../../table/Table.vue";
  import api from "../../../axios";
  
  import { useStore } from "vuex";
  const store = useStore();
  const token = computed(() => store.state.token);
  
  const users = ref([]);
  
  const columns = [
    { name: "Name", field: "name", label: "Name" },
    { name: "Email", field: "email", label: "Email" },
    { name: "Role", field: "role", label: "Role" },
    { name: "Actions", field: "actions", label: "Actions" },
  ];
  
  
  const fetchUsers = async () => {
    try {
      const response = await api.get("/api/users", {
        headers: { Authorization: `Bearer ${token.value}` },
      });
  
      console.log("response", response.data.data);
      users.value = response.data.data;
    } catch (error) {
      console.error("Error fetching players:", error);
    }
  };

  onMounted(() => {
    fetchUsers();
  });
  
  </script>
  