<template>
  <div class="task-card" :class="{ impossible: task.status === 'impossible' }">
    <div class="task-title">{{ task.title }}</div>
    <div class="task-meta">
      <span v-if="task.assignee">👤 {{ task.assignee.name }}</span>
      <span v-if="task.due_date">📅 {{ formatDate(task.due_date) }}</span>
    </div>
    <div class="task-status">
      <select :value="task.status" @change="changeStatus($event.target.value)">
        <option value="ready">Ready</option>
        <option value="in_progress">In Progress</option>
        <option value="impossible">Impossible to fulfil</option>
      </select>
    </div>
    <div class="task-actions">
      <button @click="$emit('edit', task)">✏️ Edit</button>
      <button v-if="task.status === 'impossible'" @click="openReasonModal">📝 More info</button>
      <button @click="deleteTask" class="danger">🗑️ Delete</button>
    </div>

    <div v-if="showReason" class="modal" @click.self="showReason = false">
      <div class="modal-content">
        <h3>The reason for the impossibility of execution</h3>
        <textarea v-model="reasonText" rows="4" placeholder="Describe why the task cannot be completed..."></textarea>
        <div class="modal-buttons">
          <button @click="saveReason">Save</button>
          <button @click="showReason = false">Close</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';

const props = defineProps(['task']);
const emit = defineEmits(['edit', 'update']);

const showReason = ref(false);
const reasonText = ref(props.task.impossible_reason || '');

const openReasonModal = () => {
  reasonText.value = props.task.impossible_reason || '';
  showReason.value = true;
};

const saveReason = async () => {
  try {
    await axios.patch(`/api/tasks/${props.task.id}`, {
      impossible_reason: reasonText.value,
    });
    emit('update');
    showReason.value = false;
  } catch (error) {
    console.error(error);
    alert("Couldn't save");
  }
};

const changeStatus = async (newStatus) => {
  try {
    await axios.patch(`/api/tasks/${props.task.id}`, { status: newStatus });
    emit('update');
  } catch (error) {
    console.error(error);
    alert("Can't change");
  }
};

const formatDate = (dateStr) => {
  if (!dateStr) return '';
  const date = new Date(dateStr);
  return date.toLocaleDateString();
};

const deleteTask = async () => {
  if (!confirm('Delete a task?')) return;
  try {
    await axios.delete(`/api/tasks/${props.task.id}`);
    emit('update');
  } catch (error) {
    console.error(error);
    alert("Couldn't delete task");
  }
};
</script>

<style scoped>
.task-card {
  background: #F9F5ED;
  border-radius: 6px;
  padding: 8px;
  margin-bottom: 8px;
  box-shadow: 0 1px 2px rgba(0,0,0,0.1);
  cursor: grab;
  font-family: 'BebasNeue-Regular', sans-serif;
}
.task-card.impossible {
  border-left: 4px solid red;
  background-color: #ffe6e6;
}
.task-title {
  font-weight: bold;
  margin-bottom: 4px;
}
.task-meta {
  font-size: 12px;
  color: #666;
  margin-bottom: 8px;
  display: flex;
  gap: 8px;
}
.task-status {
  margin: 8px 0;
}
.task-status select {
  padding: 4px;
  border-radius: 4px;
}
.task-actions {
  display: flex;
  gap: 8px;
  justify-content: flex-end;
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
  z-index: 1000;
}
.modal-content {
  background: white;
  padding: 20px;
  border-radius: 8px;
  width: 400px;
}
textarea {
  width: 100%;
  margin: 10px 0;
  padding: 8px;
}
.modal-buttons {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
}

.task-actions button.danger:hover {
    background-color: #cc1f1a;
    border-color: rgba(0,0,0,0.1);

}
</style>
