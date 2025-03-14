<template>
  <q-layout view="lHh Lpr lFf">
    <q-header elevated>
      <q-toolbar>
        <q-btn
          flat
          dense
          round
          icon="menu"
          aria-label="Menu"
          @click="toggleLeftDrawer"
        />

        <q-toolbar-title>
          Blog Management
        </q-toolbar-title>

        <!-- Logout Button -->
        <q-btn
          flat
          dense
          label="Logout"
          icon="logout"
          @click="handleLogout"
        >
          <q-tooltip>Logout</q-tooltip>
        </q-btn>
      </q-toolbar>
    </q-header>

    <q-drawer
      v-model="leftDrawerOpen"
      show-if-above
      bordered
    >
      <q-list>
        <q-item-label header>
          Options
        </q-item-label>

        <EssentialLink
          v-for="link in filteredLinks"
          :key="link.title"
          v-bind="link"
        />

        <!-- Debugging Information -->
        <!-- <q-item-label header>
          Debugging Information
        </q-item-label>
        <q-item>
          <q-item-section>
            <div>Permissions: {{ authStore.permissions }}</div>
            <div>Filtered Links: {{ filteredLinks }}</div>
          </q-item-section>
        </q-item> -->
      </q-list>
    </q-drawer>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useAuthStore } from 'stores/authStore'
import { useRouter } from 'vue-router'
import EssentialLink from 'components/EssentialLink.vue'
import { api } from 'boot/axios'

const leftDrawerOpen = ref(false)
const authStore = useAuthStore()
const router = useRouter()

const linksList = [
  {
    title: 'Users',
    caption: 'Manage users',
    icon: 'person',
    name: 'UserList',
    permission: 'view-user'
  },
  {
    title: 'Add User',
    caption: 'Add a new user',
    icon: 'person_add',
    name: 'UserAdd',
    permission: 'create-user'
  },
  {
    title: 'Blogs',
    caption: 'Manage blogs',
    icon: 'article',
    name: 'BlogList',
    permission: 'view-post'
  },
  {
    title: 'Add Blog',
    caption: 'Add a new blog',
    icon: 'post_add',
    name: 'BlogAdd',
    permission: 'create-post'
  },
  {
    title: 'Roles',
    caption: 'Manage roles',
    icon: 'security',
    name: 'Roles',
    permission: 'view-roles'
  },
  {
    title: 'Permissions',
    caption: 'Manage permissions',
    icon: 'lock',
    name: 'Permissions',
    permission: 'view-permission'
  }
]

const filteredLinks = computed(() => {
  return linksList.filter(link => authStore.permissions.includes(link.permission))
})

function toggleLeftDrawer () {
  leftDrawerOpen.value = !leftDrawerOpen.value
}

function handleLogout() {
  api.post('/logout') 
    .then(() => {
      localStorage.clear() // Clear local storage
      authStore.clearUser() // Clear Pinia Store
      router.push('/login') // Redirect to login page
      // window.location.reload();
    })
    .catch(err => {
      console.error('Logout failed:', err)
    })
}

onMounted(() => {
  authStore.fetchProfile()
})
</script>
