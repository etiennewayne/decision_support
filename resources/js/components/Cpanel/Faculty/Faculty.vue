<template>
    <div>
        <div class="section">
            <div class="columns is-centered">
                <div class="column is-8">
                    <div class="box">

                        <div class="table-title">
                            LIST OF FACULTY
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

                        <div class="buttons mt-3 is-right">
                            <b-button @click="openModal" icon-left="plus" 
                                class=" is-small is-success">NEW</b-button>
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
                            detailed
                            @sort="onSort">

                            <b-table-column field="faculty_id" label="ID" v-slot="props">
                                {{ props.row.faculty_id }}
                            </b-table-column>

                            <b-table-column field="lname" label="Name" v-slot="props">
                                {{ props.row.lname }}, {{ props.row.fname }} {{ props.row.mname }}
                            </b-table-column>

                            <b-table-column field="sex" label="Sex" v-slot="props">
                                {{ props.row.sex }}
                            </b-table-column>

                            <b-table-column field="active" label="Active" v-slot="props">
                                <span v-if="props.row.active">YES</span>
                                <span v-else>NO</span>
                            </b-table-column>

                            <b-table-column label="Action" v-slot="props">
                                <div class="is-flex">
                                    <b-tooltip label="Edit" type="is-primary">
                                        <b-button class="button is-small mr-1 is-primary" tag="a" icon-right="pencil" @click="getData(props.row.faculty_id)"></b-button>
                                    </b-tooltip>
                                    <b-tooltip label="Delete" type="is-primary">
                                        <b-button class="button is-small mr-1 is-danger" icon-right="delete" @click="confirmDelete(props.row.faculty_id)"></b-button>
                                    </b-tooltip>
                                </div>
                            </b-table-column>

                            <template #detail="props">
                                <tr>
                                    <th>Course Code</th>
                                    <th>Course Description</th>
                                </tr>
                                <tr v-for="i in props.row.courses_taught" :key="`taught${i.course_taught_id}`">
                                    <td >{{ i.course_code }}</td>
                                    <td >{{ i.course_desc }}</td>
                                </tr>
                            </template>
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
                        <p class="modal-card-title">FACULTY INFORMATION</p>
                        <button
                            type="button"
                            class="delete"
                            @click="isModalCreate = false" />
                    </header>

                    <section class="modal-card-body">
                        <div class="">
                            <div class="columns">
                                <div class="column">

                                    <b-field label="Lastname" label-position="on-border"
                                            :type="this.errors.lname ? 'is-danger':''"
                                            :message="this.errors.lname ? this.errors.lname[0] : ''">
                                        <b-input v-model="fields.lname"
                                            placeholder="Lastname" required>
                                        </b-input>
                                    </b-field>

                                    <b-field label="Firstname" label-position="on-border"
                                            :type="this.errors.fname ? 'is-danger':''"
                                            :message="this.errors.fname ? this.errors.fname[0] : ''">
                                        <b-input v-model="fields.fname"
                                            placeholder="Firstname" required>
                                        </b-input>
                                    </b-field>

                                    <b-field label="Middlename" label-position="on-border"
                                            :type="this.errors.mname ? 'is-danger':''"
                                            :message="this.errors.mname ? this.errors.mname[0] : ''">
                                        <b-input v-model="fields.mname"
                                            placeholder="Middlename">
                                        </b-input>
                                    </b-field>

                                    <b-field label="Sex" label-position="on-border"
                                            :type="this.errors.sex ? 'is-danger':''"
                                            :message="this.errors.sex ? this.errors.sex[0] : ''">
                                        <b-select v-model="fields.sex"
                                            placeholder="Sex" required>

                                            <option value="MALE">MALE</option>
                                            <option value="FEMALE">FEMALE</option>
                                        </b-select>
                                    </b-field>

                                    <b-field label="Active">
                                        <b-checkbox v-model="fields.active"></b-checkbox>
                                    </b-field>

                                    <hr>

                                    <div class="subtitile">Courses Taught</div>
                                    <div v-for="(item, index) in fields.courses_taught" :key="index"
                                        class="m-2">
                                        <b-field grouped>
                                            <b-field label="Course Code" label-position="on-border">
                                                <b-input type="text" v-model="item.course_code" 
                                                    placeholder="Course Code"></b-input>
                                            </b-field>

                                            <b-field expanded label="Course Description" label-position="on-border"> 
                                                <b-input type="text" v-model="item.course_desc"
                                                    placeholder="Course Description"
                                                    expanded></b-input>

                                                    <p class="control">
                                                        <b-button @click="removeCourseTaught(index, item.course_taught_id)"
                                                            type="is-danger" icon-left="delete"></b-button>
                                                    </p>
                                            </b-field>
                                        </b-field>
                                    </div>

                                    <div class="buttons">
                                        <b-button type="is-info" class="is-small"
                                            label="Add Course"
                                            @click="addCourse"></b-button>
                                    </div>


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
            sortField: 'faculty_id',
            sortOrder: 'desc',
            page: 1,
            perPage: 10,
            defaultSortDirection: 'asc',


            global_id : 0,

            search: {
                lname: '',
            },

            isModalCreate: false,

            fields: {
                lname: null,
                fname: null,
                mname: null,
                sex: null,
                active: true,
                courses_taught: [],
            },

            errors: {},

            courseTypes: [],

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
                `lname=${this.search.lname}`,
                `perpage=${this.perPage}`,
                `page=${this.page}`
            ].join('&')

            this.loading = true
            axios.get(`/cpanel/get-faculty?${params}`)
                .then(({ data }) => {
                    this.data = [];
                    let currentTotal = data.total;

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
            this.clearFields();
            this.errors = {};
        },


        submit: function(){
            if(this.global_id > 0){
                //update
                axios.put('/cpanel/faculty/'+this.global_id, this.fields).then(res=>{
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
                axios.post('/cpanel/faculty', this.fields).then(res=>{
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


        addCourse(){
            //alert('test')
            this.fields.courses_taught.push({
                course_taught_id: 0,
                course_code: '',
                course_desc: ''
            });
        },

        removeCourseTaught(index, data_id){
            this.$buefy.dialog.confirm({
                title: 'DELETE?',
                message: 'Are you sure you want to remove this?',
                onConfirm: ()=>{
                    if(data_id > 0){
                        axios.delete('/cpanel/course-taught/' + data_id).then(res=>{
                            this.fields.courses_taught.splice(index, 1);
                        })
                    }else{
                        this.fields.courses_taught.splice(index, 1);
                    }
                }
            });
        },


        //alert box ask for deletion
        confirmDelete(delete_id) {
            this.$buefy.dialog.confirm({
                title: 'DELETE!',
                type: 'is-danger',
                message: 'Are you sure you want to delete this data?',
                cancelText: 'Cancel',
                confirmText: 'Delete',
                onConfirm: () => this.deleteSubmit(delete_id)
            });
        },
        //execute delete after confirming
        deleteSubmit(delete_id) {
            axios.delete('/cpanel/faculty/' + delete_id).then(res => {
                this.loadAsyncData();
            }).catch(err => {
                if (err.response.status === 422) {
                    this.errors = err.response.data.errors;
                }
            });
        },

        clearFields(){
            this.fields.lname = ''
            this.fields.fname = ''
            this.fields.mname = ''
            this.fields.sex = ''
            this.fields.active = true

            this.fields.courses_taught = []
        },


        //update code here
        getData: function(data_id){
            this.clearFields();
            this.global_id = data_id;
            this.isModalCreate = true;

            //nested axios for getting the address 1 by 1 or request by request
            axios.get('/cpanel/faculty/'+data_id).then(res=>{
                this.fields = res.data;
                this.fields.active = res.data.active == 1 ? true : false;
                //load city first
            });
        },
    },

    mounted() {
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
