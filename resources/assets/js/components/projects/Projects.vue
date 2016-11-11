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
        <tr>
            <td></td>
            <td></td>
            <td></td>
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
        mounted() {
            this.$http.get('/api/projects').then((response) => {
                // success callback
                console.log(response)
            }, (response) => {
                // error callback
            });
            console.log('Projects component ready.')
        }
    }
</script>
