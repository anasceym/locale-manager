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
                <a href="#" class="btn btn-xs btn-primary">Update</a>
                <a href="#" class="btn btn-xs btn-danger">Delete</a>
            </td>
        </tr>
        </tbody>
    </table>
</template>

<script>
    export default {

        data: function () {
            return {
                projects: [],
                isLoading: false
            }
        },

        methods: {

            fetchProjects: function() {

                this.isLoading = true

                this.$http.get('/api/projects').then((response) => {

                    this.projects = response.data.data

                    this.isLoading = false
                }, (response) => {

                    this.isLoading = false
                });
            }
        },

        mounted: function () {

            this.fetchProjects()

            console.log('Projects component ready.')
        }
    }
</script>
