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
                newNamespaceName: ''
            }
        },

        methods: {
            addProjectNamespaceClickHandler() {

                if (this.isValidatedNewNamespaceData()) {

                    this.$http.post(`/api/projects/${this.projectId}/namespaces`, {
                        name: this.newNamespaceName
                    }).then((response) => {

                        this.modalAddNamespace.modal('hide')
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
            }
        },

        props: [
            'project-id'
        ],

        mounted() {

            this.modalAddNamespace = $('#modalProjectNamespace')

            console.log('Projects namespace component ready.')
        },

        updated() {

        }
    }
</script>
