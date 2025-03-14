<template>
    <q-page>
      <q-card v-if="user">
        <q-card-section>
          <div class="text-h6">
            User Details
            <q-btn
              flat
              icon="edit"
              color="primary"
              class="q-ml-auto"
              @click="toggleEditMode"
            />
          </div>
        </q-card-section>
  
        <!-- Show user details when not in edit mode -->
        <q-card-section v-if="!isEditing">
          <div><strong>ID:</strong> {{ user.id }}</div>
          <div><strong>Name:</strong> {{ user.name }}</div>
          <div><strong>Email:</strong> {{ user.email }}</div>
          <div><strong>Role:</strong> {{ user.role || 'N/A' }}</div>
        </q-card-section>
  
        <!-- Show user edit form when in edit mode -->
        <q-card-section v-if="isEditing">
          <UserDetailEditComponent
            :user="user"
            @submit="updateUserDetails"
            @cancel="toggleEditMode"
          />
        </q-card-section>
      </q-card>
    </q-page>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue'
  import { useRoute } from 'vue-router'
  import { api } from 'boot/axios'
  import { Notify } from 'quasar'
  
  import UserDetailEditComponent from 'components/users/UserDetailEditComponent.vue'
  
  const user = ref(null)
  const loading = ref(false)
  const isEditing = ref(false)
  const route = useRoute()
  
  const fetchUserDetails = async (id) => {
    loading.value = true
    try {
      const response = await api.get(`/users/${id}`)
      user.value = response.data.user
    } catch (error) {
      console.error('Failed to fetch user details:', error)
      Notify.create({
        type: 'negative',
        message: 'Failed to fetch user details.'
      })
    } finally {
      loading.value = false
    }
  }
  
  const toggleEditMode = () => {
    isEditing.value = !isEditing.value
  }
  
  const updateUserDetails = async (updatedUser) => {
    try {
      await api.post(`/users/${updatedUser.id}`, updatedUser)
      Notify.create({
        type: 'positive',
        message: 'User details updated successfully.'
      })
      toggleEditMode()  // Toggle back to view mode
      fetchUserDetails(updatedUser.id)  // Re-fetch user details
    } catch (error) {
      console.error('Failed to update user details:', error)
      Notify.create({
        type: 'negative',
        message: 'Failed to update user details.'
      })
    }
  }
  
  onMounted(() => {
    const userId = route.params.id
    fetchUserDetails(userId)
  })
  </script>
  
  <style scoped>
  .q-btn {
    float: right;
  }
  </style>
  