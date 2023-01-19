<template>
    <div>
        <div class="section">
            <div class="columns is-centered">
                <div class="column is-10">
                    <div class="box">

                        <div class="table-title">
                            COURSE LIST
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
                                    <b-select v-model="sortOrder" @input="loadAsyncData">
                                        <option value="asc">ASC</option>
                                        <option value="desc">DESC</option>

                                    </b-select>
                                </b-field>
                            </div>

                            <div class="level-right">
                                <div class="level-item">
                                    <b-field label="Search">
                                        <b-input type="text"
                                                 v-model="search.course" placeholder="Search Course"
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
                            <b-button tag="a" href="/cpanel/users/create" icon-left="plus" class="is-success is-small">NEW</b-button>
                        </div>

                        <b-table
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

                            <b-table-column field="course_id" label="ID" v-slot="props">
                                {{ props.row.course_id }}
                            </b-table-column>

                            <b-table-column field="course_code" label="Course" v-slot="props">
                                {{ props.row.course.course_code }} ({{ props.row.course.course_type }})
                            </b-table-column>

                            <b-table-column field="course_desc" label="Description" v-slot="props">
                                {{ props.row.course.course_desc }}
                            </b-table-column>

                            <b-table-column field="faculty_name" label="Faculty Assign" v-slot="props">
                                {{ props.row.faculty.lname }}, {{ props.row.faculty.fname }} {{ props.row.faculty.mname }}
                            </b-table-column>

                            <b-table-column field="room" label="Room" v-slot="props">
                                {{ props.row.room.room }}
                            </b-table-column>

                            <b-table-column field="schedule_time" label="Schedule Time" v-slot="props">
                                {{ props.row.start_time | formatTime }} - {{ props.row.end_time | formatTime }}
                            </b-table-column>

                            <b-table-column field="days" label="Days" v-slot="props">
                                <span class="days" v-if="props.row.mon">M</span>
                                <span class="days" v-if="props.row.tue">T</span>
                                <span class="days" v-if="props.row.wed">W</span>
                                <span class="days" v-if="props.row.thu">TH</span>
                                <span class="days" v-if="props.row.fri">F</span>
                                <span class="days" v-if="props.row.sat">SAT</span>
                                <span class="days" v-if="props.row.sun">SUN</span>
                            </b-table-column>

                        </b-table>
                    </div>
                </div><!--col -->
            </div><!-- cols -->
        </div><!--section div-->






    </div>
</template>

<script>

export default{
    data() {
        return{
            data: [],
            total: 0,
            loading: false,
            sortField: 'course_id',
            sortOrder: 'desc',
            page: 1,
            perPage: 5,
            defaultSortDirection: 'asc',

            search: {
                course: '',
            },

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
                `course=${this.search.course}`,
                `perpage=${this.perPage}`,
                `page=${this.page}`
            ].join('&')

            this.loading = true
            axios.get(`/cpanel/get-course-list?${params}`)
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




        //alert box ask for deletion
        confirmDelete(delete_id) {
            this.$buefy.dialog.confirm({
                title: 'DELETE!',
                type: 'is-danger',
                message: 'Are you sure you want to delete this data?',
                cancelText: 'Cancel',
                confirmText: 'Delete user account?',
                onConfirm: () => this.deleteSubmit(delete_id)
            });
        },
        //execute delete after confirming
        deleteSubmit(delete_id) {
            axios.delete('/users/' + delete_id).then(res => {
                this.loadAsyncData();
            }).catch(err => {
                if (err.response.status === 422) {
                    this.errors = err.response.data.errors;
                }
            });
        },

        loadOffices(){
            axios.get('/get-user-offices').then(res=>{
                this.offices = res.data
            });
        }


    },

    mounted() {
        this.loadAsyncData();
    }
}
</script>


<style scoped>

    .table > tbody > tr {
        /* background-color: blue; */
        transition: background-color 0.5s ease;
    }

    .table > tbody > tr:hover {
        background-color: rgb(233, 233, 233);

    }

</style>
