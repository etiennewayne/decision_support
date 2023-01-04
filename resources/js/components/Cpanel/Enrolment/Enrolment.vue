<template>
    <div>
        <div class="section">
            <div class="columns is-centered">
                <div class="column is-10">
                    <div class="box">

                        <div class="table-title">
                            LIST OF STUDENT ENROLED
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
                                                 v-model="search.lname" placeholder="Search Lastname"
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

                        <div class="columns">
                            <div class="column">
                                <b-field label="Academic Year">
                                    <b-select v-model="search.aycode">
                                        <option v-for="(item, index) in acadYears" :key="index" :value="item.code">{{ item.code }}</option>
                                    </b-select>
                                     <p class="control">
                                        <b-tooltip label="Search" type="is-success">
                                            <b-button type="is-primary" icon-right="account-filter" @click="loadAsyncData"/>
                                        </b-tooltip>
                                    </p>
                                </b-field>
                            </div>
                        </div>

                        <div class="buttons mt-3 is-right">
                            <b-button tag="a" href="/cpanel/enrolment/create" icon-left="plus" class=" is-small is-success">NEW</b-button>
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
                            detailed
                            :default-sort-direction="defaultSortDirection"
                            @sort="onSort">

                            <b-table-column field="enrolment_id" label="ID" v-slot="props">
                                {{ props.row.enrolment_id }}
                            </b-table-column>

                           
                            <b-table-column field="code" label="A.Y." v-slot="props">
                                {{ props.row.academic_year.code }}
                            </b-table-column>

                            <b-table-column field="student_ref" label="Student Id" v-slot="props">
                                {{ props.row.student.student_ref }}
                            </b-table-column>

                            <b-table-column field="course_code" label="Name" v-slot="props">
                                {{ props.row.student.lname }}, {{ props.row.student.fname }} {{ props.row.student.mname }}
                            </b-table-column>

                            <b-table-column field="sex" label="Sex" v-slot="props">
                                {{ props.row.student.sex }}
                            </b-table-column>

                            <b-table-column field="program_code" label="Program Code" v-slot="props">
                                {{ props.row.student.program.program_code }}
                            </b-table-column>

           

                            <b-table-column label="Action" v-slot="props">
                                <div class="is-flex">
                                    <b-tooltip label="Edit" type="is-primary">
                                        <b-button class="button is-small mr-1 is-primary" tag="a" icon-right="pencil" :href="`/cpanel/schedules/${props.row.enrolment_id}/edit`"></b-button>
                                    </b-tooltip>
                                    <b-tooltip label="Delete" type="is-primary">
                                        <b-button class="button is-small mr-1 is-danger" icon-right="delete" @click="confirmDelete(props.row.enrolment_id)"></b-button>
                                    </b-tooltip>
                                    
                                </div>
                            </b-table-column>

                            <template #detail="props">
                                <tr>
                                    <td>Program Description</td>
                                    <td>Faculty Assigned</td>
                                </tr>
                                <tr v-if="props.row.faculty">
                                    <td>{{ props.row.program.program_desc }}</td>
                                    <td>
                                        <span>
                                            {{ props.row.faculty.fname }} {{ props.row.faculty.mname }} {{ props.row.faculty.lname }}
                                        </span>
                                    </td>
                                </tr>
                            </template>
                        </b-table>



                    </div>
                </div><!--col -->
            </div><!-- cols -->
        </div><!--section div-->


    </div>
</template>

<script>

export default{
    props: ['propAcadYears'],
    data() {
        return{
            data: [],
            total: 0,
            loading: false,
            sortField: 'enrolment_id',
            sortOrder: 'desc',
            page: 1,
            perPage: 5,
            defaultSortDirection: 'asc',


            global_id : 0,

            search: {
                aycode: '',
                course: '',
                lname: '',
            },

            acadYears: [],

            modalAssignFaculty: false,

            fields: {},
            errors: {},

            btnClass: {
                'is-success': true,
                'button': true,
                'is-loading':false,
            },

        }

    },

    methods: {
        /*
        * Load async data
        */
        loadAsyncData() {
            const params = [
                `sort_by=${this.sortField}.${this.sortOrder}`,
                `aycode=${this.search.aycode}`,
                `course=${this.search.course}`,
                `perpage=${this.perPage}`,
                `page=${this.page}`
            ].join('&')

            this.loading = true
            axios.get(`/cpanel/get-student-enrollees?${params}`)
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


        submit: function(){
            if(this.global_id > 0){
                //update
                axios.put('/cpanel/program/'+this.global_id, this.fields).then(res=>{
                    if(res.data.status === 'updated'){
                        this.$buefy.dialog.alert({
                            title: 'UPDATED!',
                            message: 'Successfully updated.',
                            type: 'is-success',
                            onConfirm: () => {
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
                })
            }else{
                //INSERT HERE
                axios.post('/cpanel/program', this.fields).then(res=>{
                    if(res.data.status === 'saved'){
                        this.$buefy.dialog.alert({
                            title: 'SAVED!',
                            message: 'Successfully saved.',
                            type: 'is-success',
                            confirmText: 'OK',
                            onConfirm: () => {

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
                message: 'Are you sure you want to delete this schedule?',
                cancelText: 'Cancel',
                confirmText: 'Delete?',
                onConfirm: () => this.deleteSubmit(delete_id)
            });
        },
        //execute delete after confirming
        deleteSubmit(delete_id) {
            axios.delete('/cpanel/enrolment/' + delete_id).then(res => {
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
            //nested axios for getting the address 1 by 1 or request by request
            axios.get('/cpanel/program/'+data_id).then(res=>{
                this.fields = res.data;
                //load city first

            });
        },

        loadAcadYear(){
            this.acadYears = JSON.parse(this.propAcadYears);
        },




    },

    mounted() {
        this.loadAsyncData();
        this.loadAcadYear();
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
