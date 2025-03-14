<template>
  <q-page>
    <q-card>
      <q-card-section class="flex items-center justify-between">
        <div class="text-h6">User List</div>
        <q-btn
          icon="add"
          label="Add User"
          color="primary"
          @click="navigateToAddUser"
        />
      </q-card-section>

      <q-table
        :rows="users"
        :columns="columns"
        row-key="id"
        :loading="loading"
        v-model:page="pagination.page"
        v-model:rows-per-page="pagination.rowsPerPage"
        :rows-number="pagination.rowsNumber"
      >
        <template v-slot:body-cell-actions="props">
          <q-td :props="props">
            <q-btn flat icon="edit" @click="editUser(props.row)" />
            <q-btn flat icon="delete" color="negative" @click="deleteUser(props.row)" />
          </q-td>
        </template>
      </q-table>
    </q-card>
  </q-page>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { api } from 'boot/axios'
import { Notify } from 'quasar'
import { useRouter } from 'vue-router'

const users = ref([])
const loading = ref(false)
const pagination = ref({
  page: 1,
  rowsPerPage: 10,
  rowsNumber: 0,
})

const columns = [
  { name: 'id', required: true, label: 'ID', align: 'left', field: row => row.id, format: val => `${val}`, sortable: true },
  { name: 'name', required: true, label: 'Name', align: 'left', field: row => row.name, format: val => `${val}`, sortable: true },
  { name: 'email', required: true, label: 'Email', align: 'left', field: row => row.email, format: val => `${val}`, sortable: true },
  { name: 'role', required: true, label: 'Role', align: 'left', field: row => row.role, format: val => `${val || 'N/A'}`, sortable: true },
  { name: 'actions', label: 'Actions', align: 'center' }
]

const router = useRouter()

const fetchUsers = async () => {
  loading.value = true
  try {
    const response = await api.get('/users', {
      params: {
        page: pagination.value.page,
        limit: pagination.value.rowsPerPage,
      }
    })
    users.value = response.data.users
    pagination.value.rowsNumber = response.data.total
  } catch (error) {
    console.error('Failed to fetch users:', error)
    Notify.create({
      type: 'negative',
      message: 'Failed to fetch users.'
    })
  } finally {
    loading.value = false
  }
}

const editUser = (user) => {
  // Redirect to edit page when edit button is clicked
  router.push(`/users/${user.id}`)
}

const deleteUser = async (user) => {
  try {
    await api.delete(`/users/${user.id}`)
    Notify.create({
      type: 'positive',
      message: 'User deleted successfully.'
    })
    fetchUsers()
  } catch (error) {
    console.error('Failed to delete user:', error)
    Notify.create({
      type: 'negative',
      message: 'Failed to delete user.'
    })
  }
}

const navigateToAddUser = () => {
  router.push('/users/add')
}
onMounted(() => {
  fetchUsers()
})
</script>
