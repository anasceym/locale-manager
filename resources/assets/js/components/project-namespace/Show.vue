<template>
    <div>
        <div class="row">
            <div class="col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Project {{project.name}}

                        <a class="btn btn-danger btn-xs pull-right" :href="'/projects/'+projectId">Back to project</a>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">Namespace : </label>
                                    <p class="form-control-static">{{namespace.name}} ({{namespace.name_key}})</p>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <h4>Actions</h4>
                                <a href="#" class="btn btn-primary">Import</a>
                                <a href="#" class="btn btn-success">Export</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Translations

                        <a href="#" class="btn btn-primary btn-xs pull-right" @click="openAddTranslationClickHandler">Add translation key</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Translation Key</th>
                                <th>en</th>
                                <th>fr</th>
                                <th>my</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modalAddTranslation" tabindex="-1" role="dialog"
             aria-labelledby="modalAddTranslation">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Add translation key</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="control-label">Translation key : </label>
                            <input type="text" class="form-control" v-model="newTranslationKey">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click="addNewTranslationClickHandler">Add</button>
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
                project: {},
                namespace: {},
                componentName: 'Project namespace details',
                newTranslationKey: ''
            }
        },

        methods: {
            fetchProject() {
                this.$http.get(`/api/projects/${this.projectId}`).then((response) => {

                    if (response.status === 200) {
                        this.project = response.body
                    }
                }, (response) => {

                })
            },

            fetchNamespace() {
                this.$http.get(`/api/projects/${this.projectId}/namespaces/${this.namespaceId}`).then((response) => {

                    if (response.status === 200) {

                        this.namespace = response.body
                    }
                }, (response) => {

                })
            },

            openAddTranslationClickHandler(event) {

                event.preventDefault()

                this.modalAddTranslation.modal()
            },

            addNewTranslationClickHandler(event) {

                event.preventDefault()

                if (this.isValidatedNewTranslationData()) {
                    
                    console.log('Adding new translation')
                }
            },

            isValidatedNewTranslationData() {

                if (this.newTranslationKey === '') {

                    return false
                }
                return true
            }
        },

        props: [
            'project-id',
            'namespace-id'
        ],

        mounted() {

            this.fetchProject()

            this.fetchNamespace()

            this.modalAddTranslation = $('#modalAddTranslation')

            console.log(`${this.componentName} component ready.`)
        },

        updated() {

        }
    }
</script>
