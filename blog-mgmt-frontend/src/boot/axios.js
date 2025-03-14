import { defineBoot } from '#q-app/wrappers'
import axios from 'axios'

// Create an axios instance with the Laravel API base URL.
const api = axios.create({ baseURL: 'http://localhost:8000/api/' })

// Add a request interceptor to include the access token from localStorage.
api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('access_token')
    if (token) {
      config.headers['Authorization'] = `Bearer ${token}`
    }
    return config
  },
  (error) => Promise.reject(error)
)

export default defineBoot(({ app }) => {
  // Allows using this.$axios in Vue Options API
  app.config.globalProperties.$axios = axios
  // Allows using this.$api for API requests with the token attached
  app.config.globalProperties.$api = api
})

export { api }
