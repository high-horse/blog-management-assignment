<template>
    <q-page class="padding-all">
      <q-card>
        <q-card-section>
          <div class="text-h6">Add User</div>
        </q-card-section>
  
        <q-form @submit.prevent="submitForm" class="padding-all">
          <q-input
            v-model="localUser.name"
            label="Name"
            :rules="['required']"
          />
          <q-input
            type="email"
            v-model="localUser.email"
            label="Email"
            :rules="['required', 'email']"
          />
          
          <q-input
            type="password"
            v-model="localUser.password"
            label="Password"
            :rules="['required', 'password']"
          />
          
          <!-- Dropdown for Role -->
          <q-select
            v-model="localUser.role"
            :options="roles"
            label="Role"
            :rules="['required']"
            option-label="name"  
            option-value="id"  
            emit-value
            map-options
          />
          
          <div class="q-mt-md">
            <q-btn type="submit" label="Save" color="primary" :loading="loading" />
            <q-btn flat label="Cancel" @click="cancelEdit" color="secondary" />
          </div>
        </q-form>
      </q-card>
    </q-page>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue'
  import { useRouter } from 'vue-router'
  import { api } from 'boot/axios'
  import { Notify } from 'quasar'
  
  const localUser = ref({
    name: '',
    email: '',
    role: null,
    password: ''
  })
  const roles = ref([])  // Store the fetched roles
  const loading = ref(false)
  const router = useRouter()
  
  const fetchRoles = async () => {
    loading.value = true
    try {
      const response = await api.get('/resource/roles')
      roles.value = response.data.roles  // Assuming the API response has a "roles" array
    } catch (error) {
      console.error('Failed to fetch roles:', error)
      Notify.create({
        type: 'negative',
        message: 'Failed to fetch roles.'
      })
    } finally {
      loading.value = false
    }
  }
  
  onMounted(() => {
    fetchRoles()  // Fetch roles when component is mounted
  })
  
  const submitForm = async () => {
    loading.value = true
    try {
      await api.post('/users', localUser.value)
      Notify.create({
        type: 'positive',
        message: 'User added successfully.'
      })
      router.push('/users')
    } catch (error) {
      console.error('Failed to add user:', error)
      Notify.create({
        type: 'negative',
        message: 'Failed to add user.'
      })
    } finally {
      loading.value = false
    }
  }
  
  const cancelEdit = () => {
    router.push('/users')
  }
  </script>
  
  <style scoped>
  .q-form {
    width: 100%;
  }
  .padding-all{
    padding: 20px;
  }
  </style>