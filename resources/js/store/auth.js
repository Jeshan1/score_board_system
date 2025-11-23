// src/store/auth.js → FINAL PERFECT VERSION

import { createStore } from 'vuex'
import api from '@/axios'

const store = createStore({
  state: {
    user: JSON.parse(localStorage.getItem('user')) || null,
    token: localStorage.getItem('token') || null,
  },

  getters: {
    isAuthenticated: state => !!state.token,
    user: state => state.user,
    role: state => state.user?.role || null,
    isAdmin: state => state.user?.role === 'admin',
    isTeamManager: state => state.user?.role === 'team_manager',
    isReferee: state => state.user?.role === 'referee',
  },

  mutations: {
    SET_USER(state, user) {
      state.user = user
      user 
        ? localStorage.setItem('user', JSON.stringify(user))
        : localStorage.removeItem('user')
    },
    SET_TOKEN(state, token) {
      state.token = token
      if (token) {
        localStorage.setItem('token', token)
        api.defaults.headers.common['Authorization'] = `Bearer ${token}`
      } else {
        localStorage.removeItem('token')
        delete api.defaults.headers.common['Authorization']
      }
    },
  },

  actions: {
    async login({ commit }, credentials) {
      try {
        const res = await api.post('/api/user/login', credentials)
        const { token, user } = res.data

        commit('SET_TOKEN', token)
        commit('SET_USER', user)

        return { success: true }
      } catch (err) {
        return {
          success: false,
          message: err.response?.data?.message || 'Login failed'
        }
      }
    },

    async logout({ commit }) {
      try {
        await api.post('/api/user/logout')
      } catch (err) {
        console.error('Logout error:', err)
      } finally {
        commit('SET_TOKEN', null)
        commit('SET_USER', null)
      }
    },

    async fetchUser({ commit, state }) {
      if (!state.token) return

      try {
        const res = await api.get('/api/user')
        commit('SET_USER', res.data)
      } catch (err) {
        commit('SET_TOKEN', null)
        commit('SET_USER', null)
      }
    }
  }
})

export { store } 