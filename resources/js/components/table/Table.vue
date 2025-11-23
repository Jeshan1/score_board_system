<template>
    <div class="p-6">
      <h1 class="text-3xl font-bold mb-6">{{ title }}</h1>

      <div class="flex justify-between mb-4">
        <!-- Existing Create Button -->
        <button
          v-if="canCreate"
          @click="emit('openModal')"
          class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
        >
          Create {{ title }}
        </button>
        
        <!-- Slot content (like Create User) aligned to the right -->
        <slot name="header"></slot>
      </div>
  
      <table class="min-w-full bg-white border rounded-lg">
        <thead>
          <tr class="bg-gray-200 text-gray-700">
            <template v-for="column in columns" :key="column">
                <th class="py-2 px-4 border">{{ column.label }}</th>
            </template>
          </tr>
        </thead>
        <tbody>
          <template v-if="items.length > 0">
            <tr v-for="item in items" :key="item.id" class="text-gray-700">
            <template v-for="column in columns" :key="column">
                <td v-if="column?.field !== 'actions'" class="py-2 px-4 border text-center">
                   <!-- Handle the case for manager separately -->
                  <template v-if="column.field === 'manager'">
                    {{ item.manager ? item.manager.name : '-' }}
                  </template>
                  <!-- For other columns, just display the value -->
                  <template v-else>
                    {{ item[column?.field] }}
                  </template>
                </td>
                <td v-else class="py-2 px-4 border space-x-2 text-center">
                    <button
                        v-if="canEdit"
                        @click="emit('openModal', item)"
                        class="bg-yellow-400 px-2 py-1 rounded hover:bg-yellow-500"
                    >
                        Edit
                    </button>
                    <router-link
                      v-if="title === 'Games' && canUpdateEvents"
                      :to="{ name: 'game.events.update', params: { id: item.id } }"
                      class="bg-yellow-400 px-2 py-1 rounded hover:bg-yellow-500 text-white"
                    >
                      Update Event
                    </router-link>
                    <!-- Referee Status Control Dropdown -->
                    <select
                      v-if="title === 'Games' && canUpdateEvents"
                      :value="item.status"
                      @change="updateMatchStatus(item, $event)"
                      class="px-3 py-1.5 border rounded-md text-sm font-medium focus:outline-none focus:ring-2 focus:ring-blue-500"
                      :class="statusSelectClass(item.status)"
                    >
                      <option value="scheduled"   :disabled="item.status !== 'scheduled'">Scheduled</option>
                      <option value="ongoing"     :disabled="!['scheduled', 'paused'].includes(item.status)">Start / Resume</option>
                      <option value="paused"      :disabled="item.status !== 'ongoing'">Pause</option>
                      <option value="completed"   :disabled="item.status !== 'ongoing'">End Match</option>
                    </select>
                    <button
                        v-if="canDelete"
                        @click="emit('delete', item.id)"
                        class="bg-red-500 px-2 py-1 rounded hover:bg-red-600 text-white"
                    >
                        Delete
                    </button>
                </td>
            </template>
            
          </tr>
          </template>
          <template v-else>
            <tr>
              <td colspan="4" class="py-2 px-4 border text-center">
                No Data available.
              </td>
            </tr>
          </template>
        </tbody>
      </table>
  
    </div>
  </template>
  
  <script setup>
    import { computed, onMounted } from 'vue';
    import api from '../../axios';
  
    import { useStore } from 'vuex';
    const store = useStore();
    const token = computed(() => store.state.token);

  
    const emit = defineEmits(["openModal", "delete","refresh-games"]);
  
    const props = defineProps({
        title: { type: String, required: true },
        columns: { type: Array, required: true },
        data: { type: Array, required: true },
        permissions: {
          type: Object,
          default: () => ({ create: true, edit: true, delete: true, updateEvents: true })
        }
    });

    const items = computed(() => {
        return props.data;
    });

    // Role-based permissions
    const isAdmin = computed(() => store.getters.isAdmin)
    const isTeamManager = computed(() => store.getters.isTeamManager)
    const isReferee = computed(() => store.getters.isReferee)

    // Dynamic permissions based on title + role
    const canCreate = computed(() => {
      if (!props.permissions.create) return false
      if (isAdmin.value) return true
      if (props.title === 'Players' || props.title === 'Teams') return isTeamManager.value
      return false
    })

    const canEdit = computed(() => {
      if (!props.permissions.edit) return false
      if (isAdmin.value) return true
      if (props.title === 'Players' || props.title === 'Teams') return isTeamManager.value
      return false
    })

    const canDelete = computed(() => {
      if (!props.permissions.delete) return false
      if (isAdmin.value) return true
      return false // only admin can delete
    })

    const canUpdateEvents = computed(() => {
      if (!props.permissions.updateEvents) return false
      return isAdmin.value || isReferee.value
    })

    // Styling for select dropdown
  const statusSelectClass = (status) => ({
    'scheduled': 'bg-gray-100 text-gray-900',
    'ongoing':   'bg-green-100 text-green-900',
    'paused':    'bg-yellow-100 text-yellow-900',
    'completed': 'bg-blue-100 text-blue-900',
  }[status])

  // Update status via API
const updateMatchStatus = async (game, event) => {
  const newStatus = event.target.value

  if (newStatus === game.status) return

  // Confirm end match
  if (newStatus === 'completed') {
    if (!confirm(`End match?\nFinal Score: ${game.team_a_score} - ${game.team_b_score}\nThis cannot be undone.`)) {
      event.target.value = game.status // revert
      return
    }
  }

  try {
    await api.put(`/api/match/${game.id}/status`, {
      status: newStatus
    }, {
      headers: { Authorization: `Bearer ${token.value}` }
    })

    emit('refresh-games')

  } catch (err) {
    alert('Failed to update match status')
    console.error(err)
    event.target.value = game.status // revert on error
  }
}

  
  </script>
  