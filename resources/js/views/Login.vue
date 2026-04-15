<template>
  <div class="login-container">
    <h1>Login</h1>
    <form @submit.prevent="login">
      <input v-model="email" type="email" placeholder="Email" required />
      <input v-model="password" type="password" placeholder="Password" required />
      <button type="submit">Login</button>
    </form>
    <div class="register">
        <router-link to="/register">Don't have an account? Register</router-link>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../stores/auth.js';

const email = ref('');
const password = ref('');
const router = useRouter();
const authStore = useAuthStore();

const login = async () => {
  try {
    const response = await axios.post('/api/login', {
      email: email.value,
      password: password.value,
    });
    authStore.setUser(response.data.user, response.data.access_token);
    router.push('/');
  } catch (error) {
    alert('Login failed');
  }
};
</script>

<style scoped>
.login-container {
    font-family: 'BebasNeue-Regular', sans-serif;
    background-color: #2a2a2a;
    padding: 20px;
    font-family: 'BebasNeue-Regular', sans-serif;
    min-height: 100vh;
    margin: 0;
}
h1 {
    color: #5E83AE;
}
input {
    background-color: #f9f5ed;
    color: #2a2a2a;
    font-family: 'BebasNeue-Regular', sans-serif;
    font-size: medium;
    height: 40px;
    width: 180px;
    border-radius: 20px;
    border: none;
    text-align: center;
    margin: 20px;

}
button {
    background-color: #F9F5ED;
    color: #2a2a2a;
    border: none;
    height: 40px;
    width: 100px;
    border-radius: 15px;
    margin-right: 20px;
    font-family: 'BebasNeue-Regular';
    font-size: medium;
    font-weight: 500;
}
button:hover {
    background-color: #f4ead7;
}
a {
    text-decoration: none;
    color: inherit;
    display: block;
    color: #5E83AE;
}
</style>
