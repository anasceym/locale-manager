<template>
    <div>
        <div class="panel panel-default">
            <div class="panel-heading">
                Project namespaces

                <a class="btn btn-primary btn-xs pull-right" href="#" @click="openModalNewNamespaceClickHandler">Add new namespace</a>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <td>#</td>
                        <td>Name</td>
                        <td>Actions</td>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-if="!projectNamespaces.length">
                        <td colspan="4">
                            <span>No project's namespaces created</span>
                        </td>
                    </tr>
                    <tr v-for="(namespace, index) in projectNamespaces">
                        <td>{{index+1}}</td>
                        <td>{{namespace.name}}</td>
                        <td>
                            <a class="btn btn-primary btn-xs" :href="'/projects/'+projectId+'/namespaces/'+namespace.id">Show</a>
                            <button type="button" class="btn btn-danger btn-xs" @click="removeProjectNamespaceClickHandler($event, index, namespace)">Remove</button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal fade" id="modalProjectNamespace" tabindex="-1" role="dialog" aria-labelledby="modalProjectNamespace">
            <div class="modal-dialog modal-sm   " role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Add namespace</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="control-label">Namespace name :</label>
                            <input type="text" class="form-control" v-model="newNamespaceName">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" @click="addProjectNamespaceClickHandler">Add</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {

        data() {
            return {
                newNamespaceName: '',
                projectNamespaces: []
            }
        },

        methods: {
            addProjectNamespaceClickHandler() {

                if (this.isValidatedNewNamespaceData()) {

                    this.$http.post(`/api/projects/${this.projectId}/namespaces`, {
                        name: this.newNamespaceName
                    }).then((response) => {

                        this.fetchProjectNamespaces().then(() => {

                            this.modalAddNamespace.modal('hide')
                        })
                    }, (response) => {

                    })
                }
            },
            isValidatedNewNamespaceData() {

                if (this.newNamespaceName === '') {

                    return false;
                }

                return true;
            },
            openModalNewNamespaceClickHandler() {

                this.modalAddNamespace.modal()
            },
            removeProjectNamespaceClickHandler(event, index, namespace) {

                event.preventDefault()

                if (confirm('Are you sure want to remove this namespace?')) {

                    this.$http.delete(`/api/projects/${this.projectId}/namespaces/${namespace.id}`).then((response) => {

                        if (response.status === 204) {

                            this.projectNamespaces.splice(index, 1)
                        }
                    }, (response) => {

                    })
                }
            },
            fetchProjectNamespaces() {

                return new Promise((resolve, reject) => {
                    this.$http.get(`/api/projects/${this.projectId}/namespaces`).then((response) => {

                        if (response.status === 200) {

                            this.projectNamespaces = response.body
                        }

                        return resolve(true)
                    }, (response) => {

                        return resolve(true)
                    })
                })
            }
        },

        props: [
            'project-id'
        ],

        mounted() {

            this.modalAddNamespace = $('#modalProjectNamespace')

            this.fetchProjectNamespaces()

            console.log('Projects namespace component ready.')
        },

        updated() {

        }
    }
</script>
