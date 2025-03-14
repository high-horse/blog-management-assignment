<template>
  <q-page>
    <q-card v-if="blog">
      <q-card-section>
        <div class="text-h6">
          Blog Details
          <q-btn
            flat
            icon="edit"
            color="primary"
            class="q-ml-auto"
            @click="toggleEditMode"
          />
        </div>
      </q-card-section>

      <!-- Show blog details when not in edit mode -->
      <q-card-section v-if="!isEditing">
        <div><strong>ID:</strong> {{ blog.id }}</div>
        <div><strong>Title:</strong> {{ blog.title }}</div>
        <div><strong>Description:</strong> {{ blog.description }}</div>
        <div><strong>Content:</strong> {{ blog.content }}</div>
        <div><strong>Author:</strong> {{ blog.user_name }}</div>
        <div><strong>Published At:</strong> {{ blog.created_at }}</div>
      </q-card-section>

      <!-- Show blog edit form when in edit mode -->
      <q-card-section v-if="isEditing">
        <BlogDetailEditComponent
          :blog="blog"
          @submit="updateBlogDetails"
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

import BlogDetailEditComponent from 'components/blog/BlogDetailEditComponent.vue'

const blog = ref(null)
const loading = ref(false)
const isEditing = ref(false)
const route = useRoute()

const fetchBlogDetails = async (id) => {
  loading.value = true
  try {
    const response = await api.get(`/blog/${id}`)
    blog.value = response.data.blog
  } catch (error) {
    console.error('Failed to fetch blog details:', error)
    Notify.create({
      type: 'negative',
      message: 'Failed to fetch blog details.'
    })
  } finally {
    loading.value = false
  }
}

const toggleEditMode = () => {
  isEditing.value = !isEditing.value
}

const updateBlogDetails = async (updatedBlog) => {
  try {
    await api.put(`/blog/${updatedBlog.id}`, updatedBlog)
    Notify.create({
      type: 'positive',
      message: 'Blog details updated successfully.'
    })
    toggleEditMode()  // Toggle back to view mode
    fetchBlogDetails(updatedBlog.id)  // Re-fetch blog details
  } catch (error) {
    console.error('Failed to update blog details:', error)
    Notify.create({
      type: 'negative',
      message: 'Failed to update blog details.'
    })
  }
}

onMounted(() => {
  const blogId = route.params.id
  fetchBlogDetails(blogId)
})
</script>

<style scoped>
.q-btn {
  float: right;
}
</style>