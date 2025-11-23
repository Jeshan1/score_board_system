<template>
    <div class="flex h-screen">
      <!-- Sidebar -->
      <SideBar />
  
      <div class="flex-1 flex flex-col">
        <!-- Topbar -->
        <TopBar />
  
        <!-- Main Content (Dynamic content goes here) -->
        <div class="fixed top-[4.3rem] left-[250px] right-0">
          <router-view></router-view>
        </div>
      </div>
    </div>
</template>

<script setup>

import SideBar from '../components/admin/SideBar.vue';
import TopBar from '../components/admin/TopBar.vue';
</script>