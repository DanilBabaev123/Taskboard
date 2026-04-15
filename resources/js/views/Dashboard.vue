<template>
    <div class="dashboard-container">
        <div>
            <h1>Dashboard</h1>
            <button @click="showCreateTeam = true" class="CreateTeam">New Team</button>
        <div v-if="teams.length === 0">
        <p>No teams yet. Create your first team!</p>
        </div>
        <div v-for="team in teams" :key="team.id" style="margin: 10px 0;" class="team-card">
            <router-link :to="`/teams/${team.id}`">
                <strong>{{ team.name }}</strong>
            </router-link>
        </div>
        <div v-if="showCreateTeam" style="border: 1px solid #ccc; padding: 20px; margin-top: 20px;" class="showCreateTeam">
            <h3>Create Team</h3>
            <input v-model="newTeamName" placeholder="Team name">
            <button @click="createTeam" class="btnCreateTeam">Create</button>
            <button @click="showCreateTeam = false" class="btnCancelCreateTeam">Cancel</button>
        </div>
    </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useAuthStore } from '../stores/auth';



const teams = ref([]);
const showCreateTeam = ref(false);
const newTeamName = ref('');
const authStore = useAuthStore();

const loadTeams = async () => {
  try {
    const response = await axios.get('/api/teams');
    teams.value = response.data;
  } catch (error) {
    console.error('Failed to load teams', error);
    if (error.response && error.response.status === 401) {
      authStore.logout();
    }
  }
};

const createTeam = async () => {
  if (!newTeamName.value.trim()) return;
  try {
    await axios.post('/api/teams', { name: newTeamName.value });
    newTeamName.value = '';
    showCreateTeam.value = false;
    loadTeams();
  } catch (error) {
    console.error('Failed to create team', error);
    alert('Could not create team');
  }
};


const goBack = () => {
  router.back();
};




onMounted(loadTeams);
</script>

<style scoped>
.dashboard-container {
    background-color: #2a2a2a;
    padding: 20px;
    font-family: 'BebasNeue-Regular', sans-serif;
    min-height: 100vh;
    margin: 0;
}

h1 {
    color: #5E83AE;
}
p {
    color: #5E83AE;
}
.CreateTeam {
    background-color:#F9F5ED;
    color: #2a2a2a;
    font-weight: 600px;
    font-family: 'BebasNeue-Regular', sans-serif;
    border: none;
    width: 120px;
    height: 60px;
    border-radius: 20px;
    font-size: medium;
}
.CreateTeam:hover {
    background-color: #f4ead7;
}

a {
  text-decoration: none;
  color: inherit;
  display: block;
}

.team-card {
  background: #F9F5ED;
  border-radius: 12px;
  padding: 16px;
  margin-bottom: 12px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  transition: transform 0.2s, box-shadow 0.2s;
  cursor: pointer;
}

.team-card:hover {
    background-color: #f4ead7;
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(0,0,0,0.1);
}
.showCreateTeam {
    background-color: #F9F5ED;

}
.btnCreateTeam {
    background-color: #2a2a2a;
    color: #F9F5ED;
    border: none;
    height: 50px;
    width: 100px;
    border-radius: 15px;
    margin-right: 20px;
    margin-left: 20px;
    font-family: 'BebasNeue-Regular';
    font-size: medium;
    font-weight: 500;
}
.btnCreateTeam:hover {
    background-color: #333232;
}
.btnCancelCreateTeam {
    background-color: #2a2a2a;
    color: #F9F5ED;
    border: none;
    height: 50px;
    width: 100px;
    border-radius: 15px;
    margin-right: 20px;
    font-family: 'BebasNeue-Regular';
    font-size: medium;
    font-weight: 500;
}
.btnCancelCreateTeam:hover {
    background-color: #333232;
}
input {
    background-color: #2a2a2a;
    color: #F9F5ED;
    font-family: 'BebasNeue-Regular', sans-serif;
    font-size: medium;
    height: 50px;
    width: 400px;
    border-radius: 20px;
    border: none;
    text-align: center;
}
</style>
