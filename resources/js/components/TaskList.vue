<template>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Task List</h1>
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
            <button type="submit" class="btn btn-primary btn-block">Add Task</button>
        </form>

        <!-- LISTADO DE TODAS LAS TASKS -->
        <!-- <ul class="list-group mb-4">
            <li v-for="taskx in tasks" :key="taskx.id"
                class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1">{{ taskx.title }}</h5>
                    <p class="mb-1">{{ taskx.description }}</p>
                    <small class="text-muted">Assigned to: {{ taskx.user.name }}</small>
                </div>

            </li>
        </ul> -->

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
            selectedTask:null,
        };

    },
    computed: {
        ...mapState(['tasks']) // Simplificado para mapState
    },
    methods: {
        ...mapActions(['fetchTasks', 'addTask', 'completeTask', 'deleteTask']),



        addTask() {
            if (!this.newTask.title || !this.newTask.description || !this.newTask.user) {
                alert('Both title and description are required');
                return;
            }

            // Se utiliza la acción 'addTask' y luego se limpia el formulario
            this.$store.dispatch('addTask', this.newTask).then(() => {
                this.newTask.title = '';
                this.newTask.description = '';
                this.newTask.user = '';

               

                // this.fetchTasks().then(() => {
                //     console.log(this.tasksOne); // Debería mostrar las tareas en la consola
                // }).catch(error => {
                //     console.error('Error fetching tasks:', error);
                // });

            }).catch(error => {
                console.error('Error adding task:', error);
            });

        },
        completeTask(taskId) {
            // Se utiliza la acción 'completeTask'
            console.log(taskId)
            this.$store.dispatch('completeTask', taskId).catch(error => {
                console.error('Error completing task:', error);
            });
        },
        deleteTask(taskId) {
            // Se utiliza la acción 'deleteTask'
            this.$store.dispatch('deleteTask', taskId).catch(error => {
                console.error('Error deleting task:', error);
            });
        }
    },
    mounted() {
        // this.fetchTasks().then(() => {
        //     console.log(this.tasks.value);
        // }).catch(error => {
        //     console.error('Error fetching tasks:', error);
        // });


    }


};
</script>
