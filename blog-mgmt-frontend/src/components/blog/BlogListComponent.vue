<template>
  <q-page>
    <q-card>
      <q-card-section class="flex items-center justify-between">
        <div class="text-h6">Blog List</div>
        <q-btn
          icon="add"
          label="Add Blog"
          color="primary"
          @click="navigateToAddBlog"
        />
      </q-card-section>

      <q-table
        :rows="blogs"
        :columns="columns"
        row-key="id"
        :loading="loading"
        v-model:page="pagination.page"
        v-model:rows-per-page="pagination.rowsPerPage"
        :rows-number="pagination.rowsNumber"
      >
        <template v-slot:body-cell-actions="props">
          <q-td :props="props">
            <q-btn flat icon="edit" @click="editBlog(props.row)" />
            <q-btn flat icon="delete" color="negative" @click="deleteBlog(props.row)" />
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

const blogs = ref([])
const loading = ref(false)
const pagination = ref({
  page: 1,
  rowsPerPage: 10,
  rowsNumber: 0,
})

const columns = [
  { name: 'id', required: true, label: 'ID', align: 'left', field: row => row.id, format: val => `${val}`, sortable: true },
  { name: 'title', required: true, label: 'Title', align: 'left', field: row => row.title, format: val => `${val}`, sortable: true },
  { name: 'description', required: true, label: 'Description', align: 'left', field: row => row.description, format: val => `${val}`, sortable: true },
  { name: 'user_name', required: true, label: 'Author', align: 'left', field: row => row.user_name, format: val => `${val}`, sortable: true },
  { name: 'created_at', required: true, label: 'Published At', align: 'left', field: row => row.created_at, format: val => `${val}`, sortable: true },
  { name: 'actions', label: 'Actions', align: 'center' }
]

const router = useRouter()

const fetchBlogs = async () => {
  loading.value = true
  try {
    const response = await api.get('/blogs', {
      params: {
        page: pagination.value.page,
        limit: pagination.value.rowsPerPage,
      }
    })
    blogs.value = response.data.blogs
    pagination.value.rowsNumber = response.data.blogs.length
  } catch (error) {
    console.error('Failed to fetch blogs:', error)
    Notify.create({
      type: 'negative',
      message: 'Failed to fetch blogs.'
    })
  } finally {
    loading.value = false
  }
}

const editBlog = (blog) => {
  // Redirect to edit page when edit button is clicked
  router.push(`/blogs/${blog.id}`)
}

const deleteBlog = async (blog) => {
  try {
    await api.delete(`/blog/${blog.id}`)
    Notify.create({
      type: 'positive',
      message: 'Blog deleted successfully.'
    })
    fetchBlogs()
  } catch (error) {
    console.error('Failed to delete blog:', error)
    Notify.create({
      type: 'negative',
      message: 'Failed to delete blog.'
    })
  }
}

const navigateToAddBlog = () => {
  router.push('/blogs/add')
}

onMounted(() => {
  fetchBlogs()
})
</script>