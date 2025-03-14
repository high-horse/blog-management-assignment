<template>
  <q-layout>
    <!-- Optional: Add a header -->
    <q-header elevated>
      <q-toolbar>
        <q-toolbar-title>My App</q-toolbar-title>
      </q-toolbar>
    </q-header>

    <!-- Optional: Add a drawer -->
    <q-drawer v-model="leftDrawerOpen" bordered>
      <q-list>
        <q-item clickable v-ripple>
          <q-item-section>Home</q-item-section>
        </q-item>
      </q-list>
    </q-drawer>

    <!-- Main content -->
    <q-page-container>
      <q-page class="flex flex-center">
        <q-card class="q-pa-md" style="max-width: 400px; width: 100%;">
          <q-card-section>
            <div class="text-h6 text-center">Login</div>
          </q-card-section>

          <q-form @submit.prevent="login">
            <q-card-section>
              <q-input v-model="email" label="Email" type="email" outlined required />
            </q-card-section>
            <q-card-section>
              <q-input v-model="password" label="Password" type="password" outlined required />
            </q-card-section>
            <q-card-actions align="center">
              <q-btn type="submit" class="w-full bg-primary text-white" label="Login" color="primary" style="width: 100%;" />
            </q-card-actions>
          </q-form>

          <q-card-section class="text-center">
            <q-btn flat label="Register" to="/register" color="primary" />
          </q-card-section>
        </q-card>
      </q-page>
    </q-page-container>
  </q-layout>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { api } from 'boot/axios'
import { Notify } from 'quasar'

const email = ref('')
const password = ref('')
const leftDrawerOpen = ref(false)
const router = useRouter()

const login = async () => {
  try {
    const response = await api.post('/login', {
      email: email.value,
      password: password.value
    })
    localStorage.setItem('access_token', response.data.access_token)
    router.push('/')
  } catch (error) {
    console.error(error)
    Notify.create({
      type: 'negative',
      message: 'Login failed.'
    })
  }
}
</script>

<style scoped>
/* Add your custom styles here */
</style>