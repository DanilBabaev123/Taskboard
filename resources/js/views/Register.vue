<template>
  <div class="register-container">
    <h1>Register</h1>
    <form @submit.prevent="register" class="registerForm">
        <input v-model="name" type="text" placeholder="Name" required />
        <input v-model="password" type="password" placeholder="Password" required />
        <input v-model="email" type="email" placeholder="Email" required />
      <button type="submit">Register</button>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../stores/auth.js';

const name = ref('');
const email = ref('');
const password = ref('');
const router = useRouter();
const authStore = useAuthStore();

const register = async () => {
  try {
    const response = await axios.post('/api/register', {
      name: name.value,
      email: email.value,
      password: password.value,
    });

    authStore.setUser(response.data.user, response.data.token);
    router.push('/');
  } catch (error) {
    const message = error.response?.data?.message || 'Registration failed';
    alert(message);
  }
};
</script>

<style scoped>
.register-container {
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
    width: 250px;
    border-radius: 20px;
    border: none;
    text-align: center;
    margin: 20px;
    display: flex;
    align-items: center;

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
    margin-left: 25px;
}
button:hover {
    background-color: #f4ead7;
}


</style>
