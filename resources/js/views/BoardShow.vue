<template>
  <div class="board-container">
    <button @click="goBack" class="back-button">← Back</button>
    <h1>Board: {{ board.name }}</h1>
    <button @click="openCreateTaskModal" class="createTask">New Task</button>

    <div class="columns">
      <!-- READY -->
      <div class="column">
        <h3>Ready</h3>
        <draggable
          v-model="tasksReady"
          group="tasks"
          item-key="id"
          @end="onDragEnd"
          class="task-list"
        >
          <template #item="{ element: task }">
            <TaskCard :task="task" @edit="openEditTaskModal" @update="loadBoard" />
          </template>
        </draggable>
      </div>

      <!-- IN PROGRESS -->
      <div class="column">
        <h3>In Progress</h3>
        <draggable
          v-model="tasksInProgress"
          group="tasks"
          item-key="id"
          @end="onDragEnd"
          class="task-list"
        >
          <template #item="{ element: task }">
            <TaskCard :task="task" @edit="openEditTaskModal" @update="loadBoard" />
          </template>
        </draggable>
      </div>

      <!-- IMPOSSIBLE -->
      <div class="column">
        <h3>Impossible to fulfil</h3>
        <draggable
          v-model="tasksImpossible"
          group="tasks"
          item-key="id"
          @end="onDragEnd"
          class="task-list"
        >
          <template #item="{ element: task }">
            <TaskCard :task="task" @edit="openEditTaskModal" @update="loadBoard" />
          </template>
        </draggable>
      </div>
    </div>

    <div v-if="showModal" class="modal" @click.self="showModal = false">
      <div class="modal-content">
        <h3>{{ editingTask ? 'Edit Task' : 'Create Task' }}</h3>
        <input v-model="form.title" placeholder="Title" />
        <textarea v-model="form.description" placeholder="Description"></textarea>
        <select v-model="form.assigned_to">
          <option :value="null">Unassigned</option>
          <option v-for="u in teamMembers" :key="u.id" :value="u.id">{{ u.name }}</option>
        </select>
        <input type="date" v-model="form.due_date" />
        <div class="modal-buttons">
          <button @click="saveTask">Save</button>
          <button @click="showModal = false">Cancel</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import axios from 'axios';
import draggable from 'vuedraggable';
import TaskCard from '../views/TaskCard.vue';

const route = useRoute();
const router = useRouter();

const board = ref({ tasks: [], team: null });
const teamMembers = ref([]);
const showModal = ref(false);
const editingTask = ref(null);
const form = ref({ title: '', description: '', assigned_to: null, due_date: '' });

const tasksReady = computed({
  get: () => board.value.tasks?.filter(t => t.status === 'ready') || [],
  set: (value) => {
    const other = board.value.tasks?.filter(t => t.status !== 'ready') || [];
    board.value.tasks = [...value, ...other];
  }
});

const tasksInProgress = computed({
  get: () => board.value.tasks?.filter(t => t.status === 'in_progress') || [],
  set: (value) => {
    const other = board.value.tasks?.filter(t => t.status !== 'in_progress') || [];
    board.value.tasks = [...other, ...value];
  }
});

const tasksImpossible = computed({
  get: () => board.value.tasks?.filter(t => t.status === 'impossible') || [],
  set: (value) => {
    const other = board.value.tasks?.filter(t => t.status !== 'impossible') || [];
    board.value.tasks = [...other, ...value];
  }
});

const loadBoard = async () => {
  try {
    const res = await axios.get(`/api/boards/${route.params.id}`);
    board.value = res.data;
    if (board.value.team) {
      const members = await axios.get(`/api/teams/${board.value.team.id}/users`);
      teamMembers.value = members.data;
    }
  } catch (error) {
    console.error(error);
  }
};

// ----- DRAG-AND-DROP -----
const onDragEnd = async (event) => {
  const { newIndex, item } = event;
  const taskId = item.__draggable_context.element.id;

  const column = event.to.closest('.column');
  const title = column?.querySelector('h3')?.innerText;
  let newStatus = null;
  if (title === 'Ready') newStatus = 'ready';
  else if (title === 'In Progress') newStatus = 'in_progress';
  else if (title === 'Impossible to fulfil') newStatus = 'impossible';

  if (!newStatus) {
    console.error("Couldn't determine the status");
    return;
  }

  try {
    await axios.patch(`/api/tasks/${taskId}/move`, {
      status: newStatus,
      new_order: newIndex,
    });
    await loadBoard();
  } catch (error) {
    console.error('Movement error:', error);
    alert('The task cannot be moved');
    await loadBoard();
  }
};

const openCreateTaskModal = () => {
  editingTask.value = null;
  form.value = { title: '', description: '', assigned_to: null, due_date: '' };
  showModal.value = true;
};

const openEditTaskModal = (task) => {
  editingTask.value = task;
  form.value = {
    title: task.title,
    description: task.description || '',
    assigned_to: task.assigned_to,
    due_date: task.due_date?.split('T')[0] || '',
  };
  showModal.value = true;
};

const saveTask = async () => {
  try {
    if (editingTask.value) {
      await axios.put(`/api/tasks/${editingTask.value.id}`, form.value);
    } else {
      await axios.post(`/api/boards/${route.params.id}/tasks`, form.value);
    }
    await loadBoard();
    showModal.value = false;
  } catch (err) {
    console.error('Error saving the task:', err);
    alert('Error saving the task:');
  }
};

const goBack = () => {
  router.back();
};


onMounted(loadBoard);
</script>

<style scoped>
.board-container {
    background-color: #2a2a2a;
    font-family: 'BebasNeue-Regular', sans-serif;
    padding: 20px;
    min-height: 100vh;
}
.createTask {
    background-color:#F9F5ED;
    color: #5E83AE;
    font-family: 'BebasNeue-Regular', sans-serif;
    border: none;
    width: 120px;
    height: 60px;
    border-radius: 20px;
    font-size: medium;
}
.createTask:hover {
    background-color: #f4ead7;
}
h1 {
    color: #F9F5ED;
}
.button {
    color: #0552ac;
}
.back-button {
    background-color: #F9F5ED;
    border: none;
    margin-bottom: 20px;
    padding: 5px 10px;
    cursor: pointer;
    color: #5E83AE;
    font-family: 'BebasNeue-Regular';
    border-radius: 10px;
    width: 100px;
    height: 40px;
    font-size: medium;

}
.back-button:hover {
    background-color: #f4ead7;
}
.columns {
    color: #F9F5ED;
    display: flex;
    gap: 20px;
    align-items: flex-start;
    margin-top: 20px;
}
.column {
  flex: 1;
  background: #5E83AE;
  border-radius: 8px;
  padding: 10px;
  min-height: 400px;
  color: #2a2a2a;
}
.task-list {
  min-height: 300px;
}
.modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0,0,0,0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  color: #2a2a2a;
}
.modal-content {
  background: #F9F5ED;
  color: #2a2a2a;
  padding: 20px;
  border-radius: 8px;
  width: 400px;
}
input, textarea, select {
  width: 100%;
  margin-bottom: 10px;
  padding: 8px;
  font-family: 'BebasNeue-Regular', sans-serif;
  color: #2a2a2a;
}
.modal-buttons {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
}
</style>
