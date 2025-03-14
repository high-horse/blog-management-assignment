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
            <div class="text-h6 text-center">Register</div>
          </q-card-section>

          <q-form @submit.prevent="register">
            <q-card-section>
              <q-input v-model="name" label="Name" outlined required />
            </q-card-section>
            <q-card-section>
              <q-input v-model="email" label="Email" type="email" outlined required />
            </q-card-section>
            <q-card-section>
              <q-input v-model="password" label="Password" type="password" outlined required />
            </q-card-section>
            <q-card-section>
              <q-input v-model="password_confirmation" label="Confirm Password" type="password" outlined required />
            </q-card-section>
            <q-card-actions align="center">
              <q-btn type="submit" label="Register" color="primary" style="width: 100%;"  />
            </q-card-actions>
          </q-form>

          <q-card-section class="text-center">
            <q-btn flat label="Login" @click="goToLogin" color="primary" />
          </q-card-section>
        </q-card>
      </q-page>
    </q-page-container>
  </q-layout>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { api } from 'boot/axios';
import { Notify } from 'quasar';

const name = ref('');
const email = ref('');
const password = ref('');
const password_confirmation = ref('');
const leftDrawerOpen = ref(false);
const router = useRouter();

const register = async () => {
  try {
    const response = await api.post('/register', {
      name: name.value,
      email: email.value,
      password: password.value,
      password_confirmation: password_confirmation.value,
    });

    localStorage.setItem('access_token', response.data.access_token);
    router.push('/');
  } catch (error) {
    console.error(error);
    Notify.create({
      type: 'negative',
      message: error.response?.data?.message || 'Registration failed.',
    });
  }
};

const goToLogin = () => {
  router.push('/login');
};
</script>

<style scoped>
/* Add your custom styles here */
</style>