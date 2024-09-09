<template>
    <div class="container mt-5">
        <a href="/users">Add user</a>
        <h1 class="text-center mb-4">{{ isEditing ? "Edit Task" : "Task List" }}</h1>
        <ul class="list-group mb-4">
            <li v-for="task in tasks" :key="task.id"
                class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1">{{ task.title }}</h5>
                    <p class="mb-1">{{ task.description }}</p>
                    <small class="text-muted">Assigned to: {{ task.user.name }}</small>
                </div>
                <div>
                    <button class="btn btn-success btn-sm mr-2" @click="completeTask(task.id)">Complete</button>
                    <button class="btn btn-danger btn-sm" @click="deleteTask(task.id)">Delete</button>
                </div>
            </li>
        </ul>
        <form @submit.prevent="addTask" class="card card-body">
            <div class="form-group">
                <input v-model="newTask.title" class="form-control" placeholder="Task Title" required>
            </div>
            <div class="form-group">
                <input v-model="newTask.description" class="form-control" placeholder="Task Description" required>
            </div>
            <div class="form-group">
                <input v-model="newTask.user" class="form-control" placeholder="Assigned User" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">
                {{ isEditing ? "Update Task" : "Add Task" }}
            </button>

            <button v-if="isEditing" @click="resetForm" class="btn btn-secondary btn-block mt-2">
                Cancel
            </button>
        </form>

        <!-- LISTADO DE TODAS LAS TASKS -->
        <div class="form-group">
            <label for="filter">Filter Tasks:</label>
            <select v-model="filter" class="form-control" id="filter">
                <option value="all" selected>All Tasks</option>
                <option value="completed">Completed Tasks</option>
                <option value="notCompleted">Not Completed Tasks</option>
            </select>
        </div>
        <div v-if="tasksDataBase">
            <li v-for="taskDataBase in filteredTasks" :key="taskDataBase.id"
                class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1">{{ taskDataBase.title }}</h5>
                    <p class="mb-1">Description: {{ taskDataBase.description }}</p>
                    <p class="mb-1">Completed: {{ (taskDataBase.completed == 1) ? 'Si' : 'No' }}</p>
                    <small class="text-muted">Assigned to: {{ taskDataBase.user.name }}</small>
                </div>
                <div>
                    <button class="btn btn-primary btn-sm" @click="selectTaskForUpdate(taskDataBase)">Selected</button>
                </div>
            </li>
        </div>

    </div>
</template>

<script>

import { mapState, mapActions } from 'vuex';

export default {
    data() {
        return {
            newTask: {
                title: '',
                description: '',
                user: ''
            },
            filter: 'all',
            selectedTaskId: null,
            isEditing: false
        };

    },
    computed: {
        ...mapState(['tasks', 'tasksDataBase']), // Simplificado para mapState

        filteredTasks() {
            
            if (this.filter === 'completed') {
                return this.tasksDataBase.filter(task => task.completed === 1);
            } else if (this.filter === 'notCompleted') {
                return this.tasksDataBase.filter(task => task.completed === 0);
            } else {
                return this.tasksDataBase;
            }

        }
    },
    methods: {
        ...mapActions(['fetchTasks', 'addTask', 'completeTask', 'deleteTask', 'updateTask']),

        addTask() {
            // Se utiliza la acción 'addTask' y luego se limpia el formulario
            if (!this.newTask.title || !this.newTask.description || !this.newTask.user) {
                alert('Both title and description are required');
                return;
            }
            if (this.isEditing) {

                this.$store.dispatch('updateTask', { id: this.selectedTaskId, data: this.newTask }).then(() => {
                    this.resetForm()
                    this.$store.dispatch('fetchTasks');

                }).catch(error => {
                    console.error('Error adding task:', error);
                });
            } else {
                this.$store.dispatch('addTask', this.newTask).then(() => {
                    this.resetForm()
                    this.$store.dispatch('fetchTasks');

                }).catch(error => {
                    console.error('Error adding task:', error);
                });
            }



        },

        completeTask(taskId) {
            // Se utiliza la acción 'completeTask'

            this.$store.dispatch('completeTask', taskId).catch(error => {
                console.error('Error completing task:', error);
            });
            this.$store.dispatch('fetchTasks');
        },
        deleteTask(taskId) {
            // Se utiliza la acción 'deleteTask'
            this.$store.dispatch('deleteTask', taskId).catch(error => {
                console.error('Error deleting task:', error);
            });
            this.$store.dispatch('fetchTasks');
        },
        selectTaskForUpdate(task) {
            this.newTask.title = task.title;
            this.newTask.description = task.description;
            this.newTask.user = task.user.email;
            this.isEditing = true;
            this.selectedTaskId = task.id;
        },
        resetForm() {
            this.newTask = { title: '', description: '', user: '' };
            this.isEditing = false;
            this.selectedTaskId = null;
        },

    },
    mounted() {
        this.fetchTasks();
    }


};
</script>
