<template>
  <q-item clickable v-ripple :to="{ name: name }">
    <q-item-section avatar>
      <q-icon :name="icon" />
    </q-item-section>

    <q-item-section>
      <q-item-label>{{ title }}</q-item-label>
      <q-item-label caption>{{ caption }}</q-item-label>
    </q-item-section>
  </q-item>
</template>

<script setup>
defineProps({
  title: String,
  caption: String,
  icon: String,
  name: String
})
</script>
