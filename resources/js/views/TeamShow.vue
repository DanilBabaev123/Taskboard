<template>
  <div class="teamshow-container">
    <h1>Team: {{ team.name }}</h1>
    <button @click="goBack" class="back-button">← Back</button>
    <button @click="createBoard">New Board</button>
    <div v-for="board in team.boards" :key="board.id">
      <router-link :to="{ name: 'board.show', params: { id: board.id } }">
        {{ board.name }}
      </router-link>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import axios from 'axios';

const route = useRoute();
const router = useRouter();
const team = ref({ boards: [] });

const loadTeam = async () => {
    const response = await axios.get(`/api/teams/${route.params.id}`);
    team.value = response.data;
};

const createBoard = async () => {
    const name = prompt('Board name?');
    if (name) {
        await axios.post(`/api/teams/${route.params.id}/boards`, { name });
        loadTeam();
    }
};

const goBack = () => {
  router.back();
};



onMounted(loadTeam);
</script>


<style scoped>
.teamshow-container {
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
a {
    text-decoration: none;
    color: inherit;
    display: block;
    color: #5E83AE;
    display: flex;
    text-align: center;
    margin-top: 20px;
    margin-left: 20px;
    font-size: large;
    font-weight: 800;

}

</style>
