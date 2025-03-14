<template>
  <q-page class="padding-all">
    <q-card>
      <q-card-section>
        <div class="text-h6">Add Blog</div>
      </q-card-section>

      <q-form @submit.prevent="submitForm" class="padding-all">
        <q-input
          v-model="localBlog.title"
          label="Title"
          :rules="['required']"
        />
        <q-input
          v-model="localBlog.description"
          label="Description"
          :rules="['required']"
        />
        <q-input
          v-model="localBlog.content"
          label="Content"
          type="textarea"
          :rules="['required']"
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
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { api } from 'boot/axios'
import { Notify } from 'quasar'

const localBlog = ref({
  title: '',
  description: '',
  content: ''
})
const loading = ref(false)
const router = useRouter()

const submitForm = async () => {
  loading.value = true
  try {
    await api.post('/blog', localBlog.value)
    Notify.create({
      type: 'positive',
      message: 'Blog added successfully.'
    })
    router.push('/blogs')
  } catch (error) {
    console.error('Failed to add blog:', error)
    Notify.create({
      type: 'negative',
      message: 'Failed to add blog.'
    })
  } finally {
    loading.value = false
  }
}

const cancelEdit = () => {
  router.push('/blogs')
}
</script>

<style scoped>
.q-form {
  width: 100%;
}
.padding-all {
  padding: 20px;
}
</style>