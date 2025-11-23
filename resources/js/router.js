import { createRouter, createWebHistory } from 'vue-router'
import { store } from '@/store/auth'  // your fixed store

import Login from '@/pages/LoginPage.vue'
import PublicLayout from '@/Layouts/LandingLayout.vue'
import AdminPanelLayout from '@/Layouts/AdminLayout.vue'

import MatchesList from '@/components/landing/MatchList.vue'
import Dashboard from '@/components/admin/DashBoard.vue'
import Leagues from '@/components/admin/league/Index.vue'
import Players from '@/components/admin/player/Index.vue'
import Users from '@/components/admin/users/Index.vue'
import UserCreate from '@/components/admin/users/Create.vue'
import Teams from '@/components/admin/team/Index.vue'
import Games from '@/components/admin/game/Index.vue'
import GameEventUpdate from '@/components/admin/game/GameEventUpdate.vue'

const routes = [
  {
    path: '/',
    component: PublicLayout,
    meta: { requiresAuth: false },
    children: [
      { path: '', name: 'home', component: MatchesList }
    ]
  },

  {
    path: '/login',
    name: 'login',
    component: Login,
    meta: { requiresAuth: false }
  },

  {
    path: '/admin',
    component: AdminPanelLayout,
    meta: { requiresAuth: true },
    children: [
      { path: '', redirect: { name: 'dashboard' } },
      { path: 'dashboard', name: 'dashboard', component: Dashboard },

      // ADMIN ONLY
      {
        path: 'leagues',
        name: 'leagues.index',
        component: Leagues,
        meta: { roles: ['admin'] }
      },
      {
        path: 'users',
        name: 'users.index',
        component: Users,
        meta: { roles: ['admin'] }
      },
      {
        path: 'users/create',
        name: 'users.create',
        component: UserCreate,
        meta: { roles: ['admin'] }
      },

      // ADMIN + TEAM_MANAGER
      {
        path: 'teams',
        name: 'teams.index',
        component: Teams,
        meta: { roles: ['admin', 'team_manager'] }
      },
      {
        path: 'players',
        name: 'players.index',
        component: Players,
        meta: { roles: ['admin', 'team_manager'] }
      },

      // ADMIN + REFEREE
      {
        path: 'games',
        name: 'games.index',
        component: Games,
        meta: { roles: ['admin', 'referee'] }
      },
      {
        path: 'games/:id/events/update',
        name: 'game.events.update',
        component: GameEventUpdate,
        props: true,
        meta: { roles: ['admin', 'referee'] }
      },
    ]
  },

  // 404 fallback
  { path: '/:pathMatch(.*)*', redirect: '/' }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

// FINAL PERFECT NAVIGATION GUARD
router.beforeEach(async (to, from, next) => {
  const token = store.state.token
  const user = store.state.user

  // Load user if token exists but user not in state
  if (token && !user) {
    await store.dispatch('fetchUser')
  }

  const requiresAuth = to.matched.some(record => record.meta.requiresAuth)
  const allowedRoles = to.matched
    .filter(r => r.meta.roles)
    .flatMap(r => r.meta.roles)

  // Not logged in → go login
  if (requiresAuth && !token) {
    return next('/login')
  }

  // Logged in but trying to access login → redirect
  if (to.path === '/login' && token) {
    return next('/admin')
  }

  // Role check: if route has roles defined
  if (allowedRoles.length > 0) {
    const userRole = store.getters.role

    if (!allowedRoles.includes(userRole)) {
      // Redirect based on role
      if (userRole === 'team_manager') return next('/admin/teams')
      if (userRole === 'referee') return next('/admin/games')
      return next('/') // fallback
    }
  }

  next()
})

export default router