<template>
    <div>
        <div class="form-group">
            <label class="label-control">Name</label>
            <input class="form-control" type="text" v-model="project.name"/>
        </div>
        <div class="form-group">
            <a @click="updateProjectButtonHandler" class="btn btn-success pull-right">Update</a>
        </div>
    </div>
</template>

<script>
    export default {

        data() {
            return {
                project: {}
            }
        },

        methods: {
            updateProjectButtonHandler() {

                if (this.isValidatedFormData()) {

                    this.$http.patch('/api/projects/' + this.projectId, {
                        name: this.project.name
                    }).then((response) => {

                        if (response.status === 200) {
                            window.location = '/projects'
                        }
                    }, (response) => {

                    });
                }
            },
            isValidatedFormData() {

                if (this.project.name === '') {

                    return false
                }

                return true
            },
            fetchProject() {
                this.$http.get('/api/projects/' + this.projectId).then((response) => {
                    this.project = response.body
                }, (response) => {

                });
            }
        },

        props: [
            'project-id'
        ],

        mounted() {

            this.fetchProject()

            console.log('Update project component ready.')
        }
    }
</script>
