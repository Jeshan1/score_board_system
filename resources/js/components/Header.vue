<template>
    <header class="bg-green-600 text-white p-6 shadow">
      <div class=" flex justify-between items-center">
        <h1 class="text-2xl font-bold">Yenya Soft League Scoreboard</h1>
        <nav>
          <!-- <router-link  class="px-4 hover:underline">Home</router-link> -->
          <!-- <router-link to="/leagues" class="px-4 hover:underline">Leagues</router-link> -->
          <router-link to="/login" class="px-4 hover:underline">Login</router-link>
        </nav>
      </div>
    </header>
  </template>
  
  <script>

  export default {
    name: "Header",
  };
  </script>
  