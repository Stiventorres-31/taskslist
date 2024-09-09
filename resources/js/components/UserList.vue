<template>
    <div class="container mt-5">
        <a href="/">Add tasks</a>
        <h1 class="text-center mb-4">{{ isEditing ? "Edit User" : "Users List" }}</h1>

        <form @submit.prevent="addUser" class="card card-body">
            <div class="form-group">
                <input v-model="newUser.name" class="form-control" placeholder="Name" required>
            </div>
            <div class="form-group">
                <input v-model="newUser.email" class="form-control" placeholder="Email" required>
            </div>

            <button type="submit" class="btn btn-primary btn-block">
                {{ isEditing ? "Update User" : "Add User" }}
            </button>

            <button v-if="isEditing" @click="resetForm" class="btn btn-secondary btn-block mt-2">
                Cancel
            </button>
        </form>
        <ul class="list-group mb-4">
            <li v-for="error in errors" 
                class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1 text-danger">{{ error }}</h5>
                </div>
                
            </li>
        </ul>
        <!-- LISTADO DE TODAS LOS USUARIOS -->
        <ul class="list-group mb-4">
            <li v-for="user in users" :key="user.id"
                class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1">{{ user.name }}</h5>
                    <p class="mb-1">{{ user.email }}</p>
                    <p class="mb-1">{{ user.id }}</p>

                </div>
                <div>
                    <button class="btn btn-danger btn-sm" @click="deleteUser(user.id)">Delete</button>
                    <button class="btn btn-primary btn-sm" @click="selectUserForUpdate(user)">Selected</button>
                </div>
            </li>
        </ul>


    </div>
</template>

<script>

import { mapState, mapActions } from 'vuex';

export default {
    data() {
        return {
            newUser: {
                name: '',
                email: ''
            },

            selectedUserId: null,
            isEditing: false
        };

    },
    computed: {
        ...mapState(['users','errors']), // Simplificado para mapState
    },
    methods: {
        ...mapActions(['fetchUsers', 'updateUser', 'addUser', 'deleteUser']),

        addUser() {
            if (!this.newUser.name || !this.newUser.email) {
                alert('Both name and email are required');
                return;
            }

            if (this.isEditing) {

                this.$store.dispatch('updateUser', { id: this.selectedUserId, data: this.newUser }).then(() => {
                    this.resetForm()
                 
                    // this.$store.dispatch('fetchUsers');

                }).catch(error => {
                    console.error('Error updated task:', error);
                });
            } else {
                this.$store.dispatch('addUser', this.newUser).then(response => {
                    this.resetForm()
             
                    // this.$store.dispatch('fetchUsers');

                }).catch(error => {
                    console.error('Error adding user:', error);
                });
            }

        },

        deleteUser(userId) {
            this.$store.dispatch('deleteUser', userId).then(() => {

               
                this.resetForm()
            }).catch(error => {
                    console.error('Error deleted user:', error);
                });
        },
        selectUserForUpdate(User) {
            this.newUser.name = User.name;
            this.newUser.email = User.email;
            this.isEditing = true;
            this.selectedUserId = User.id;
        },
        resetForm() {
            this.newUser = { name: '', email: '' };
            this.isEditing = false;
            this.selectedUserId = null;
        },

    },
    mounted() {
        this.fetchUsers();
    }


};
</script>
