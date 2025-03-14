import { defineStore } from 'pinia'
import { ref } from 'vue'
import { api } from 'boot/axios'
import { useRouter } from 'vue-router'


export const useAuthStore = defineStore('auth', () => {
  const user = ref(null)
  const roles = ref([])
  const permissions = ref([])

  const router = useRouter()

  function setUser(userData) {
    user.value = userData
    roles.value = userData.roles || []
    permissions.value = userData.permissions || []
  }

  function clearUser() {
    user.value = null
    roles.value = []
    permissions.value = []
  }

  async function fetchProfile() {
    try {
      const response = await api.get('/profile')
      setUser(response.data)
    } catch (error) {
      if (error.response && error.response.status === 401) {
        clearUser()
        router.push('/login')
      } else {
        console.error('Failed to fetch profile:', error)
      }
    }
  }

  return {
    user,
    roles,
    permissions,
    setUser,
    clearUser,
    fetchProfile
  }
})