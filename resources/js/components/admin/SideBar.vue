<template>
  <nav class="bg-[#f7f7f8] h-screen fixed top-0 left-0 min-w-[250px] py-6 px-4 font-sans">
    <div class="mb-8">
      <h1 class="text-2xl font-bold text-gray-800">Yenya Admin Panel</h1>
    </div>

    <div class="overflow-auto py-6 h-full">
      <ul class="space-y-2">

        <!-- Dashboard - All authenticated users -->
        <li>
          <router-link
            to="/admin/dashboard"
            class="flex items-center px-4 py-3 rounded hover:bg-white text-black hover:text-blue-600 transition-all"
          >
            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-5 h-5 mr-4" viewBox="0 0 512 512">
              <path d="M197.332 170.668h-160C16.746 170.668 0 153.922 0 133.332v-96C0 16.746 16.746 0 37.332 0h160c20.59 0 37.336 16.746 37.336 37.332v96c0 20.59-16.746 37.336-37.336 37.336zM37.332 32A5.336 5.336 0 0 0 32 37.332v96a5.337 5.337 0 0 0 5.332 5.336h160a5.338 5.338 0 0 0 5.336-5.336v-96A5.337 5.337 0 0 0 197.332 32zm160 480h-160C16.746 512 0 495.254 0 474.668v-224c0-20.59 16.746-37.336 37.332-37.336h160c20.59 0 37.336 16.746 37.336 37.336v224c0 20.586-16.746 37.332-37.336 37.332zm-160-266.668A5.337 5.337 0 0 0 32 250.668v224A5.336 5.336 0 0 0 37.332 480h160a5.337 5.337 0 0 0 5.336-5.332v-224a5.338 5.338 0 0 0-5.336-5.336zM474.668 512h-160c-20.59 0-37.336-16.746-37.336-37.332v-96c0-20.59 16.746-37.336 37.336-37.336h160c20.586 0 37.332 16.746 37.332 37.336v96C512 495.254 495.254 512 474.668 512zm-160-138.668a5.338 5.338 0 0 0-5.336 5.336v96a5.337 5.337 0 0 0 5.336 5.332h160a5.336 5.336 0 0 0 5.332-5.332v-96a5.337 5.337 0 0 0-5.332-5.336zm160-74.664h-160c-20.59 0-37.336-16.746-37.336-37.336v-224C277.332 16.746 294.078 0 314.668 0h160C495.254 0 512 16.746 512 37.332v224c0 20.59-16.746 37.336-37.332 37.336zM314.668 32a5.337 5.337 0 0 0-5.336 5.332v224a5.338 5.338 0 0 0 5.336 5.336h160a5.337 5.337 0 0 0 5.332-5.336v-224A5.336 5.336 0 0 0 474.668 32z"/>
            </svg>
            <span>Dashboard</span>
          </router-link>
        </li>

        <!-- ADMIN ONLY -->
        <li v-if="isAdmin">
          <router-link to="/admin/leagues" class="flex items-center px-4 py-3 rounded hover:bg-white text-black hover:text-blue-600 transition-all">
            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-5 h-5 mr-4" viewBox="0 0 512 512">
              <path d="M437.02 74.98C388.668 26.63 324.379 0 256 0S123.332 26.629 74.98 74.98C26.63 123.332 0 187.621 0 256s26.629 132.668 74.98 181.02C123.332 485.37 187.621 512 256 512s132.668-26.629 181.02-74.98C485.37 388.668 512 324.379 512 256s-26.629-132.668-74.98-181.02zM111.105 429.297c8.454-72.735 70.989-128.89 144.895-128.89 38.96 0 75.598 15.179 103.156 42.734 23.281 23.285 37.965 53.687 41.742 86.152C361.641 462.172 311.094 482 256 482s-105.637-19.824-144.895-52.703zM256 269.507c-42.871 0-77.754-34.882-77.754-77.753C178.246 148.879 213.13 114 256 114s77.754 34.879 77.754 77.754c0 42.871-34.883 77.754-77.754 77.754zm170.719 134.427a175.9 175.9 0 0 0-46.352-82.004c-18.437-18.438-40.25-32.27-64.039-40.938 28.598-19.394 47.426-52.16 47.426-89.238C363.754 132.34 315.414 84 256 84s-107.754 48.34-107.754 107.754c0 37.098 18.844 69.875 47.465 89.266-21.887 7.976-42.14 20.308-59.566 36.542-25.235 23.5-42.758 53.465-50.883 86.348C50.852 364.242 30 312.512 30 256 30 131.383 131.383 30 256 30s226 101.383 226 226c0 56.523-20.86 108.266-55.281 147.934 shrinkage"/>
            </svg>
            <span>Leagues</span>
          </router-link>
        </li>

        <!-- ADMIN + TEAM_MANAGER -->
        <li v-if="isAdmin || isTeamManager">
          <router-link to="/admin/players" class="flex items-center px-4 py-3 rounded hover:bg-white text-black hover:text-blue-600 transition-all">
            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-5 h-5 mr-4" viewBox="0 0 24 24">
              <path d="M18 2c2.206 0 4 1.794 4 4v12c0 2.206-1.794 4-4 4H6c-2.206 0-4-1.794-4-4V6c0-2.206 1.794-4 4-4zm0-2H6a6 6 0 0 0-6 6v12a6 6 0 0 0 6 6h12a6 6 0 0 0 6-6V6a6 6 0 0 0-6-6z"/>
              <path d="M12 18a1 1 0 0 1-1-1V7a1 1 0 0 1 2 0v10a1 1 0 0 1-1 1z"/>
              <path d="M6 12a1 1 0 0 1 1-1h10a1 1 0 0 1 0 2H7a1 1 0 0 1-1-1z"/>
            </svg>
            <span>Players</span>
          </router-link>
        </li>

        <!-- ADMIN ONLY -->
        <li v-if="isAdmin">
          <router-link to="/admin/users" class="flex items-center px-4 py-3 rounded hover:bg-white text-black hover:text-blue-600 transition-all">
            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-5 h-5 mr-4" viewBox="0 0 510 510">
              <path d="M255 0C114.75 0 0 114.75 0 255s114.75 255 255 255 255-114.75 255-255S395.25 0 255 0zm0 459c-112.2 0-204-91.8-204-204S142.8 51 255 51s204 91.8 204 204-91.8 204-204 204z"/>
              <path d="M267.75 127.5H229.5v153l132.6 81.6 20.4-33.15-114.75-68.85z"/>
            </svg>
            <span>User Management</span>
          </router-link>
        </li>

        <!-- ADMIN + TEAM_MANAGER -->
        <li v-if="isAdmin || isTeamManager">
          <router-link to="/admin/teams" class="flex items-center px-4 py-3 rounded hover:bg-white text-black hover:text-blue-600 transition-all">
            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-5 h-5 mr-4" viewBox="0 0 512 512">
              <path d="M437.02 74.98C388.668 26.63 324.379 0 256 0S123.332 26.629 74.98 74.98C26.63 123.332 0 187.621 0 256s26.629 132.668 74.98 181.02C123.332 485.37 187.621 512 256 512s132.668-26.629 181.02-74.98C485.37 388.668 512 324.379 512 256s-26.629-132.668-74.98-181.02zM111.105 429.297c8.454-72.735 70.989-128.89 144.895-128.89 38.96 0 75.598 15.179 103.156 42.734 23.281 23.285 37.965 53.687 41.742 86.152C361.641 462.172 311.094 482 256 482s-105.637-19.824-144.895-52.703zM256 269.507c-42.871 0-77.754-34.882-77.754-77.753C178.246 148.879 213.13 114 256 114s77.754 34.879 77.754 77.754c0 42.871-34.883 77.754-77.754 77.754zm170.719 134.427a175.9 175.9 0 0 0-46.352-82.004c-18.437-18.438-40.25-32.27-64.039-40.938 28.598-19.394 47.426-52.16 47.426-89.238C363.754 132.34 315.414 84 256 84s-107.754 48.34-107.754 107.754c0 37.098 18.844 69.875 47.465 89.266-21.887 7.976-42.14 20.308-59.566 36.542-25.235 23.5-42.758 53.465-50.883 86.348C50.852 364.242 30 312.512 30 256 30 131.383 131.383 30 256 30s226 101.383 226 226c0 56.523-20.86 108.266-55.281 147.934z"/>
            </svg>
            <span>Team Management</span>
          </router-link>
        </li>

        <!-- ADMIN + REFEREE -->
        <li v-if="isAdmin || isReferee">
          <router-link to="/admin/games" class="flex items-center px-4 py-3 rounded hover:bg-white text-black hover:text-blue-600 transition-all">
            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-5 h-5 mr-4" viewBox="0 0 512 512">
              <path d="M437.02 74.98C388.668 26.63 324.379 0 256 0S123.332 26.629 74.98 74.98C26.63 123.332 0 187.621 0 256s26.629 132.668 74.98 181.02C123.332 485.37 187.621 512 256 512s132.668-26.629 181.02-74.98C485.37 388.668 512 324.379 512 256s-26.629-132.668-74.98-181.02zM111.105 429.297c8.454-72.735 70.989-128.89 144.895-128.89 38.96 0 75.598 15.179 103.156 42.734 23.281 23.285 37.965 53.687 41.742 86.152C361.641 462.172 311.094 482 256 482s-105.637-19.824-144.895-52.703zM256 269.507c-42.871 0-77.754-34.882-77.754-77.753C178.246 148.879 213.13 114 256 114s77.754 34.879 77.754 77.754c0 42.871-34.883 77.754-77.754 77.754zm170.719 134.427a175.9 175.9 0 0 0-46.352-82.004c-18.437-18.438-40.25-32.27-64.039-40.938 28.598-19.394 47.426-52.16 47.426-89.238C363.754 132.34 315.414 84 256 84s-107.754 48.34-107.754 107.754c0 37.098 18.844 69.875 47.465 89.266-21.887 7.976-42.14 20.308-59.566 36.542-25.235 23.5-42.758 53.465-50.883 86.348C50.852 364.242 30 312.512 30 256 30 131.383 131.383 30 256 30s226 101.383 226 226c0 56.523-20.86 108.266-55.281 147.934z"/>
            </svg>
            <span>Game Management</span>
          </router-link>
        </li>

        <!-- LOGOUT -->
        <li>
          <div @click="handleLogout" class="flex items-center px-4 py-3 rounded hover:bg-red-50 text-black hover:text-red-600 cursor-pointer transition-all">
            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-5 h-5 mr-4" viewBox="0 0 512 512">
              <path d="M256 0C114.84 0 0 114.84 0 256s114.84 256 256 256 256-114.84 256-256S397.16 0 256 0zm0 480C132.48 480 32 379.52 32 256S132.48 32 256 32s224 100.48 224 224-100.48 224-224 224z"/>
              <path d="M363.88 189.12l-112 112-112-112-33.94 33.94 112 112-112 112 33.94 33.94 112-112 112 112 33.94-33.94-112-112 112-112z"/>
            </svg>
            <span>Logout</span>
          </div>
        </li>

      </ul>
    </div>
  </nav>
</template>

<script setup>
import { useStore } from 'vuex'
import { useRouter } from 'vue-router'

const store = useStore()
const router = useRouter()

// Use Vuex getters — clean & reactive
const isAdmin = store.getters.isAdmin
const isTeamManager = store.getters.isTeamManager
const isReferee = store.getters.isReferee

const handleLogout = async () => {
  await store.dispatch('logout')
  router.push('/login')
}
</script>