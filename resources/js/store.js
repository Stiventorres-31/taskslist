import Vue from "vue";
import Vuex from "vuex";
import axios from "axios"; // Asegúrate de tener axios instalado

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        tasks: [], // Estado inicial para las tareas
        users: [],
        errors: {}
    },
    mutations: {
        SET_TASKS(state, tasks) {
            state.tasks = tasks; // Asegúrate de que tasks sea un array aquí
        },

        ADD_TASK(state, task) {
            state.tasks.unshift(task);

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
            // const index = state.tasks.findIndex((item) => item.id === task.id);
            // if (index !== -1) {
            //     // state.tasks.splice(index, 1, taskId); // Actualiza la tarea en la lista de tareas

            //     Vue.set(state.tasks, index, updatedTask);
            //     console.log(state.tasks[index])
            //     this.$forceUpdate();
            // }
            const index = state.tasks.findIndex((t) => t.id === task.id);
            if (index !== -1) {
                Vue.set(state.tasks, index, task);
            }
        },
        ERRORS(state, errors) {
            state.errors = errors
        },
        CLEAR_ERRORS(state) {
            state.errors = {}; // Limpia los errores
        },

        SET_USERS(state, users) {
            state.users = users;
        },

        ADD_USER(state, user) {
            state.users.unshift(user)
        },
        DELETE_USER(state, userId) {
            state.users = state.users.filter((item) => item.id !== userId);
        },
        UPDATE_USER(state, User) {
            const index = state.users.findIndex((item) => item.id === User.id)
            if (index !== -1) {
                Vue.set(state.users, index, User)
            }
        }
    },
    actions: {
        fetchTasks({ commit }) {


            axios
                .get("/tasks/list")
                .then((response) => {

                    commit("SET_TASKS", response.data);
                })
                .catch((error) => {
                    alert("Error: " + error.response?.data?.message || error.message);

                });
        },

        addTask({ commit }, task) {
            axios
                .post("/tasks", task)
                .then((response) => {
                    const data = response.data;
                    alert(data.message)
                    commit("ADD_TASK", data.result);
                })
                .catch((error) => {
                    alert("Error: " + error.response?.data?.message || error.message);

                });
        },
        updateTask({ commit }, task) {
            axios
                .put(`/tasks/${task.id}`, task.data)
                .then((response) => {
                    const data = response.data
                    commit("UPDATE_TASK", data.result);
                    console.log(data.result);
                    alert(data.message);
                })
                .catch((error) => {
                    alert("Error: " + error.response?.data?.message || error.message);
                    commit('ERRORS', error.response.data.errors);
                });
        },
        completeTask({ commit }, taskId) {
            axios
                .put(`/tasks/completed/${taskId}`)
                .then((response) => {
                    const data = response.data
                    commit("COMPLETE_TASK", data.result);
                    console.log(data.result);
                    alert(data.message);
                })
                .catch((error) => {
                    alert("Error: " + error.response?.data?.message || error.message);

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
                    alert("Error: " + error.response?.data?.message || error.message);

                });
        },


        fetchUsers({ commit }) {
            axios
                .get("/users/list")
                .then((response) => {
                    commit("SET_USERS", response.data);
                })
                .catch((error) => {
                    alert(
                        "Error: " + error.response?.data?.message ||
                        error.message
                    );

                });
        },
        addUser({ commit }, User) {
            axios.post('/users', User).then(response => {
                const data = response.data
                alert(data.message)
                commit('ADD_USER', data.result)
            }).catch((error) => {
                alert("Error: " + error.response?.data?.message || error.message);

            });
        },
        deleteUser({ commit }, userId) {
            axios.delete(`/users/${userId}`).then(response => {
                const data = response.data
                alert(data.message)
                commit("DELETE_USER", userId)
            }).catch((error) => {
                alert("Error: " + error.response?.data?.message || error.message);


            });
        },
        updateUser({ commit }, User) {

            axios.put(`/users/${User.id}`, User.data)
                .then((response) => {
                    const data = response.data;
                    commit("UPDATE_USER", data.result);
                    alert(data.message);
                })
                .catch((error) => {
                    alert("Error: " + error.response?.data?.message || error.message);
                    commit('ERRORS', error.response.data.errors);

                });
        }
    },
    getters: {
        tasks: (state) => state.tasks,
        errors: (state) => state.errors
    },
});
