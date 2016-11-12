<template>
    <div>
        <div class="form-group">
            <label class="label-control">Name</label>
            <input class="form-control" type="text" v-model="name"/>
        </div>
        <div class="form-group">
            <a @click="createProjectButtonHandler" class="btn btn-success pull-right">Create</a>
        </div>
    </div>
</template>

<script>
    export default {

        data() {
            return {
                name: ''
            }
        },

        methods: {
            createProjectButtonHandler() {

                if (this.isValidatedFormData()) {

                    this.$http.post('/api/projects', {
                        name: this.name
                    }).then((response) => {

                        if (response.status === 201) {

                            window.location = '/projects'
                        }
                    }, (response) => {

                    });
                }
            },
            isValidatedFormData() {

                if (this.name === '') {

                    return false
                }

                return true
            }
        },

        mounted() {

            console.log('New project component ready.')
        }
    }
</script>
