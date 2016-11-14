<template>
    <table class="table table-hovered">
        <thead>
        <tr>
            <th>Number</th>
            <th>Project Name</th>
            <th>Date Created</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <tr v-if="isLoading">
            <td colspan="4">
                <span>Loading...</span>
            </td>
        </tr>
        <tr v-for="(project, index) in projects">
            <td>{{index+1}}</td>
            <td>{{project.name}}</td>
            <td>{{project.created_at}}</td>
            <td>
                <a v-bind:href="'/projects/'+project.id" class="btn btn-xs btn-success">Show</a>
                <a v-bind:href="'/projects/'+project.id+'/edit'" class="btn btn-xs btn-primary">Update</a>
                <a href="#" class="btn btn-xs btn-danger" @click="deleteProjectClickHandler(project, index)">Delete</a>
            </td>
        </tr>
        </tbody>
    </table>
</template>

<script>
    export default {

        data() {
            return {
                projects: [],
                isLoading: false
            }
        },

        methods: {

            fetchProjects() {

                this.isLoading = true

                this.$http.get('/api/projects').then((response) => {

                    this.projects = response.data.data

                    this.isLoading = false
                }, (response) => {
                    this.isLoading = false
                });
            },

            deleteProjectClickHandler(project, index) {

                if (confirm("Are you sure want to delete this project?")) {

                    this.$http.delete('/api/projects/' + project.id).then((response) => {

                        if (response.status === 204) {

                            this.projects.splice(index, 1)
                        }
                    }, (response) => {

                    });
                }
            }
        },

        mounted() {

            this.fetchProjects()

            console.log('Projects component ready.')
        }
    }
</script>
