<template>
    <div>
        <div class="section">
            <div class="columns is-centered">
                <div class="column is-8">
                    <div class="box">

                        <div class="table-title">
                            LIST OF PROGRAM
                        </div>

                        <div class="level">
                            <div class="level-left">
                                <b-field label="Page">
                                    <b-select v-model="perPage" @input="setPerPage">
                                        <option value="5">5 per page</option>
                                        <option value="10">10 per page</option>
                                        <option value="15">15 per page</option>
                                        <option value="20">20 per page</option>
                                    </b-select>
                                </b-field>
                            </div>

                            <div class="level-right">
                                <div class="level-item">
                                    <b-field label="Search">
                                        <b-input type="text"
                                                 v-model="search.program_code" placeholder="Search program"
                                                 @keyup.native.enter="loadAsyncData"/>
                                        <p class="control">
                                             <b-tooltip label="Search" type="is-success">
                                            <b-button type="is-primary" icon-right="account-filter" @click="loadAsyncData"/>
                                             </b-tooltip>
                                        </p>
                                    </b-field>
                                </div>
                            </div>
                        </div>

                        <div class="buttons mt-3 is-right">
                            <b-button @click="openModal" icon-left="plus" class=" is-small is-success">NEW</b-button>
                        </div>

                        <b-table
                            striped
                            :data="data"
                            :loading="loading"
                            paginated
                            backend-pagination
                            :total="total"
                            :pagination-rounded="true"
                            :per-page="perPage"
                            @page-change="onPageChange"
                            aria-next-label="Next page"
                            aria-previous-label="Previous page"
                            aria-page-label="Page"
                            aria-current-label="Current page"
                            backend-sorting
                            :default-sort-direction="defaultSortDirection"
                            @sort="onSort">

                            <b-table-column field="program_id" label="ID" v-slot="props">
                                {{ props.row.program_id }}
                            </b-table-column>

                            <b-table-column field="program_code" label="Program Code" v-slot="props">
                                {{ props.row.program_code }}
                            </b-table-column>

                            <b-table-column field="program_desc" label="Program Description" v-slot="props">
                                {{ props.row.program_desc }}
                            </b-table-column>

                            <b-table-column label="Action" v-slot="props">
                                <div class="is-flex">
                                    <b-tooltip label="Edit" type="is-primary">
                                        <b-button class="button is-small mr-1 is-primary" tag="a" icon-right="pencil" @click="getData(props.row.program_id)"></b-button>
                                    </b-tooltip>
                                    <b-tooltip label="Delete" type="is-primary">
                                        <b-button class="button is-small mr-1 is-danger" icon-right="delete" @click="confirmDelete(props.row.program_id)"></b-button>
                                    </b-tooltip>
                                </div>
                            </b-table-column>
                        </b-table>



                    </div>
                </div><!--col -->
            </div><!-- cols -->
        </div><!--section div-->



        <!--modal create-->
        <b-modal v-model="isModalCreate" has-modal-card
            trap-focus
            :width="640"
            aria-role="dialog"
            aria-label="Modal"
            aria-modal>

            <form @submit.prevent="submit">
                <div class="modal-card">
                    <header class="modal-card-head">
                        <p class="modal-card-title">PROGRAM</p>
                        <button
                            type="button"
                            class="delete"
                            @click="isModalCreate = false" />
                    </header>

                    <section class="modal-card-body">
                        <div class="">
                            <div class="columns">
                                <div class="column">
                                    <b-field label="Program Code" label-position="on-border"
                                             :type="this.errors.program_code ? 'is-danger':''"
                                             :message="this.errors.program_code ? this.errors.program_code[0] : ''">
                                        <b-input v-model="fields.program_code"
                                                 placeholder="Program Code" required>
                                        </b-input>
                                    </b-field>

                                    <b-field label="Institute" label-position="on-border"
                                             :type="this.errors.institute_id ? 'is-danger':''"
                                             :message="this.errors.institute_id ? this.errors.institute_id[0] : ''">
                                        <b-select v-model="fields.institute_id" required
                                                  @input="loadPrograms">
                                            <option v-for="(ins, insx) in institutes"
                                                    :key="insx"
                                                    :value="ins.institute_id">{{ ins.institute }}</option>
                                        </b-select>
                                    </b-field>

                                    <b-field label="Program Description" label-position="on-border"
                                             :type="this.errors.program_desc ? 'is-danger':''"
                                             :message="this.errors.program_desc ? this.errors.program_desc[0] : ''">
                                        <b-input v-model="fields.program_desc"
                                                 placeholder="Program Description" required>
                                        </b-input>
                                    </b-field>
                                </div>
                            </div>
                        </div>
                    </section>
                    <footer class="modal-card-foot">
                        <b-button
                            label="Close"
                            @click="isModalCreate=false"/>
                        <button
                            :class="btnClass"
                            label="Save"
                            type="is-success">SAVE</button>
                    </footer>
                </div>
            </form><!--close form-->
        </b-modal>
        <!--close modal-->


    </div>
</template>

<script>

export default{
    data() {
        return{
            data: [],
            total: 0,
            loading: false,
            sortField: 'program_id',
            sortOrder: 'desc',
            page: 1,
            perPage: 5,
            defaultSortDirection: 'asc',


            global_id : 0,

            search: {
                program_code: '',
            },

            isModalCreate: false,

            fields: {},
            errors: {},

            btnClass: {
                'is-success': true,
                'button': true,
                'is-loading':false,
            },

            institutes: [],

        }

    },

    methods: {
        /*
        * Load async data
        */
        loadAsyncData() {
            const params = [
                `sort_by=${this.sortField}.${this.sortOrder}`,
                `programcode=${this.search.program_code}`,
                `perpage=${this.perPage}`,
                `page=${this.page}`
            ].join('&')

            this.loading = true
            axios.get(`/cpanel/get-programs?${params}`)
                .then(({ data }) => {
                    this.data = [];
                    let currentTotal = data.total
                    if (data.total / this.perPage > 1000) {
                        currentTotal = this.perPage * 1000
                    }

                    this.total = currentTotal
                    data.data.forEach((item) => {
                        //item.release_date = item.release_date ? item.release_date.replace(/-/g, '/') : null
                        this.data.push(item)
                    })
                    this.loading = false
                })
                .catch((error) => {
                    this.data = []
                    this.total = 0
                    this.loading = false
                    throw error
                })
        },
        /*
        * Handle page-change event
        */
        onPageChange(page) {
            this.page = page
            this.loadAsyncData()
        },

        onSort(field, order) {
            this.sortField = field
            this.sortOrder = order
            this.loadAsyncData()
        },

        setPerPage(){
            this.loadAsyncData()
        },

        openModal(){
            this.isModalCreate=true;
            this.fields = {};
            this.errors = {};
        },




        submit: function(){
            if(this.global_id > 0){
                //update
                axios.put('/cpanel/programs/'+this.global_id, this.fields).then(res=>{
                    if(res.data.status === 'updated'){
                        this.$buefy.dialog.alert({
                            title: 'UPDATED!',
                            message: 'Successfully updated.',
                            type: 'is-success',
                            onConfirm: () => {
                                this.loadAsyncData();
                                this.clearFields();
                                this.global_id = 0;
                                this.isModalCreate = false;
                            }
                        })
                    }
                }).catch(err=>{
                    if(err.response.status === 422){
                        this.errors = err.response.data.errors;
                    }
                })
            }else{
                //INSERT HERE
                axios.post('/cpanel/programs', this.fields).then(res=>{
                    if(res.data.status === 'saved'){
                        this.$buefy.dialog.alert({
                            title: 'SAVED!',
                            message: 'Successfully saved.',
                            type: 'is-success',
                            confirmText: 'OK',
                            onConfirm: () => {
                                this.isModalCreate = false;
                                this.loadAsyncData();
                                this.clearFields();
                                this.global_id = 0;
                            }
                        })
                    }
                }).catch(err=>{
                    if(err.response.status === 422){
                        this.errors = err.response.data.errors;
                    }
                });


            }
        },


        //alert box ask for deletion
        confirmDelete(delete_id) {
            this.$buefy.dialog.confirm({
                title: 'DELETE!',
                type: 'is-danger',
                message: 'Are you sure you want to delete this data?',
                cancelText: 'Cancel',
                confirmText: 'Delete program?',
                onConfirm: () => this.deleteSubmit(delete_id)
            });
        },
        //execute delete after confirming
        deleteSubmit(delete_id) {
            axios.delete('/cpanel/programs/' + delete_id).then(res => {
                this.loadAsyncData();
            }).catch(err => {
                if (err.response.status === 422) {
                    this.errors = err.response.data.errors;
                }
            });
        },

        clearFields(){
           this.fields = {};
        },


        //update code here
        getData: function(data_id){
            this.clearFields();
            this.global_id = data_id;
            this.isModalCreate = true;

            //nested axios for getting the address 1 by 1 or request by request
            axios.get('/cpanel/programs/'+data_id).then(res=>{
                this.fields = res.data;
                //load city first

            });
        },


        loadInstitute(){
            axios.get('/get-open-institutes').then(res=>{
                this.institutes = res.data
            })
        },
        loadPrograms(institute_id){
            axios.get('/get-open-programs/'+ this.fields.institute_id).then(res=>{
                this.programs = res.data
            })
        },



    },

    mounted() {
        this.loadInstitute()
        this.loadAsyncData();
    }
}
</script>


<style scoped>
/*
    .table > tbody > tr {

        transition: background-color 0.5s ease;
    }

    .table > tbody > tr:hover {
        background-color: rgb(233, 233, 233);
    } */

    .modal-card-head{
        background-color: green;
    }
    .modal-card-title{
        color: white;
    }


</style>
