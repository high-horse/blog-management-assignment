<template>
    <q-form @submit.prevent="submitForm">
      <q-input
        v-model="localUser.name"
        label="Name"
        :rules="['required']"
      />
      <q-input
        v-model="localUser.email"
        label="Email"
        :rules="['required', 'email']"
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
        <q-btn type="submit" label="Save" color="primary" />
        <q-btn flat label="Cancel" @click="cancelEdit" color="secondary" />
      </div>
    </q-form>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue'
  import { api } from 'boot/axios'
  import { Notify } from 'quasar'
  
  const props = defineProps({
    user: Object
  })
  
  const emit = defineEmits(['submit', 'cancel'])
  
  const localUser = ref({ ...props.user })
  const roles = ref([])  // Store the fetched roles
  const loading = ref(false)
  
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
  
  const submitForm = () => {
    emit('submit', localUser.value)
  }
  
  const cancelEdit = () => {
    emit('cancel')
  }
  </script>
  
  <style scoped>
  .q-form {
    width: 100%;
  }
  </style>
  