import Vue from "vue";
import Vuex from "vuex";
import axios from "axios"; // Asegúrate de tener axios instalado

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        tasks: [], // Estado inicial para las tareas
        tasksDataBase:[]
    },
    mutations: {
        SET_TASKS(state, tasks) {
            state.tasksDataBase = tasks; // Asegúrate de que tasks sea un array aquí
        },
        ADD_TASK(state, task) {
            state.tasks.push(task);
            state.tasksDataBase.push(task);
        },
        UPDATE_TASK(state, updatedTask) {
            const index = state.tasks.findIndex((t) => t.id === updatedTask.id);
            if (index !== -1) {
                Vue.set(state.tasks, index, updatedTask);
            }
        },
        DELETE_TASK(state, taskId) {
            state.tasks = state.tasks.filter((task) => task.id !== taskId);
        },

        COMPLETE_TASK(state, task) {
            const updatedTask = task;
            const index = state.tasks.findIndex((item) => item.id === task.id);
            if (index !== -1) {
               state.tasksDataBase= state.tasks.splice(index, 1, updatedTask); // Actualiza la tarea en la lista de tareas

                state.tasks = state.tasks.filter((item) => item.id !== task.id);
            }
        },
    },
    actions: {
        fetchTasks({ commit }) {
            //Todavia no esta funcionando
            axios
                .get("/tasks")
                .then((response) => {
                    commit("SET_TASKS", response.data); // response.data debe ser un array
                })
                .catch((error) => {
                    console.error("Error fetching tasks:", error);
                });
        },

        addTask({ commit }, task) {
            axios
                .post("/tasks", task)
                .then((response) => {
                    // console.log(response.data)

                    commit("ADD_TASK", response.data);
                   
                })
                .catch((error) => {
                    console.error("Error adding task:", error);
                });
        },
        updateTask({ commit }, task) {
            axios
                .put(`/tasks/${task.id}`, task)
                .then((response) => {
                    commit("UPDATE_TASK", response.data);
                })
                .catch((error) => {
                    console.error("Error updating task:", error);
                });
        },
        completeTask({ commit }, taskId) {
            axios
                .put(`/tasks/completed/${taskId}`)
                .then((response) => {
                    commit("COMPLETE_TASK", response.data.task);
                    alert(response.data.message);
                })
                .catch((error) => {
                    console.error("Error updating task:", error);
                });
        },
        deleteTask({ commit }, taskId) {
            axios
                .delete(`/tasks/${taskId}`)
                .then((response) => {
                    alert(response.data.message);
                    commit("DELETE_TASK", taskId);
                })
                .catch((error) => {
                    console.error("Error deleting task:", error);
                });
        },
    },
    getters: {
        tasks: (state) => state.tasks,
        tasksDataBase: (state) => state.tasksDataBase
    },
});
