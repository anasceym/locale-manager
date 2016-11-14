<template>
    <div>
        <div class="panel panel-default">
            <div class="panel-heading">
                Project languages

                <a class="btn btn-primary btn-xs pull-right" href="#" @click="openAddLanguageModalHandler">Add language</a>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <td>#</td>
                        <td>Language</td>
                        <td>Code</td>
                        <td>Action</td>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-if="isLoading">
                        <td colspan="4">
                            <span>Loading...</span>
                        </td>
                    </tr>
                    <tr v-if="!isLoading && !existingProjectLangs.length">
                        <td colspan="4">No project's language created</td>
                    </tr>
                    <tr v-for="(lang, index) in existingProjectLangs">
                        <td>{{index+1}}</td>
                        <td>{{lang.lang_name}}</td>
                        <td>{{lang.lang_code}}</td>
                        <td><button type="button" class="btn btn-danger btn-xs" @click="removeProjectLangClickHandler($event, index, lang)">Remove</button></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal fade" id="modalProjectLanguage" tabindex="-1" role="dialog" aria-labelledby="modalProjectLanguage">
            <div class="modal-dialog modal-sm   " role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Add language</h4>
                    </div>
                    <div class="modal-body">
                        <select class="form-control" data-plugin="selectpicker" v-model="selectedProjectLang">
                            <option :value="key" v-for="(projectLang, key) in projectLangs">{{projectLang}}</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" @click="addProjectLangClickHandler">Add</button>
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
                projectLangs: [],
                selectedProjectLang: '',
                existingProjectLangs: [],
                isLoading: false
            }
        },

        methods: {
            openAddLanguageModalHandler(event) {
                event.preventDefault()
                this.modalProjectLanguage.modal()
            },
            addProjectLangClickHandler(event) {
                event.preventDefault()

                this.$http.post(`/api/projects/${this.projectId}/lang`, {
                    lang_code: this.selectedProjectLang
                }).then((response) => {

                    if (response.status === 201) {
                        this.modalProjectLanguage.modal('hide')
                        this.fetchProjectLangs()
                    }
                }, (response) => {

                })
            },
            fetchLocales() {
                this.$http.get('/api/locales').then((response) => {

                    if (response.status === 200) {

                        this.projectLangs = response.body
                    }
                }, (response) => {

                })
            },
            fetchProjectLangs() {
                this.isLoading = true
                this.$http.get(`/api/projects/${this.projectId}/lang`).then((response) => {

                    if (response.status === 200) {

                        this.existingProjectLangs = response.body
                    }

                    this.isLoading = false
                }, (response) => {
                    this.isLoading = false
                })
            },
            removeProjectLangClickHandler(event, index, lang) {
                event.preventDefault()

                this.$http.delete(`/api/projects/${this.projectId}/lang/${lang.id}`).then((response) => {

                    if (response.status === 204) {

                        this.existingProjectLangs.splice(index, 1)
                    }
                }, (response) => {

                })
            }
        },

        props: [
            'project-id'
        ],

        mounted() {

            this.modalProjectLanguage = $('#modalProjectLanguage')

            this.fetchLocales()

            this.fetchProjectLangs()

            console.log('Projects language component ready.')
        },

        updated() {

            $('[data-plugin=selectpicker]').selectpicker('refresh')
        }
    }
</script>
