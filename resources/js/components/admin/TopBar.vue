<template>
    <div class="bg-gray-800 text-white flex items-center justify-end p-4 fixed top-0 right-0 left-[250px]">
      <!-- Right side: User Info & Dropdown -->
      <div class="relative">
        <button 
          @click="toggleDropdown" 
          class="bg-gray-700 hover:bg-gray-600 text-white py-2 px-4 rounded-full"
        >
          User
        </button>
  
        <!-- Dropdown Menu -->
        <div 
          v-if="showDropdown" 
          class="fixed right-28 top-2 w-48 bg-black text-white rounded shadow-lg z-[1000]"
        >
          <ul class="cursor-pointer bg-black p-4 rounded">
            <li>
              <a class="block px-4 py-2 hover:bg-gray-700 cursor-pointer text-white" @click="logout">
                Logout
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </template>
  
    
    <script setup>
    import { ref } from 'vue'
    import { useRouter } from 'vue-router'
    import { useStore } from 'vuex'
    
    const store = useStore()
    const router = useRouter()
    
    const showDropdown = ref(false)
    
    const toggleDropdown = () => {
      showDropdown.value = !showDropdown.value
    }
    
    const logout = async () => {
        console.log("Logging out...")
        await store.dispatch("logout")  // wait for token removal
        router.push("/")
    }

    </script>
    
   
    