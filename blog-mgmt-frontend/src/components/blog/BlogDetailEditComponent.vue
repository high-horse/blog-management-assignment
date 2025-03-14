<template>
  <q-form @submit.prevent="submitForm">
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
      <q-btn type="submit" label="Save" color="primary" />
      <q-btn flat label="Cancel" @click="cancelEdit" color="secondary" />
    </div>
  </q-form>
</template>

<script setup>
import { ref } from 'vue'

const props = defineProps({
  blog: Object
})

const emit = defineEmits(['submit', 'cancel'])

const localBlog = ref({ ...props.blog })

const submitForm = () => {
  emit('submit', localBlog.value)
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