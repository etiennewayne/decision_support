<template>
    <div>
        <b-field label="Course">
            <b-input :value="valueCourse" expanded icon="calendar-arrow-right" placeholder="SELECT COURSE" required readonly>
            </b-input>

            <p class="control">
                <b-button class="button is-primary" @click="openModal">...</b-button>
            </p>
        </b-field>


        <b-modal v-model="isModalActive" has-modal-card
                 trap-focus scroll="keep" aria-role="dialog" aria-modal>
            <div class="modal-card card-width">
                <header class="modal-card-head">
                    <p class="modal-card-title">SELECT COURSE</p>
                    <button type="button" class="delete"
                            @click="isModalActive = false" />

                </header>

                <section class="modal-card-body">
                    <div>

                        <b-field label="Search" label-position="on-border" >
                            <b-input type="text" v-model="search.courseCode" placeholder="Search Course Code..." expanded auto-focus></b-input>
                            <b-input type="text" v-model="search.courseDesc" placeholder="Search Course Description..." expanded auto-focus></b-input>
                            <p class="control">
                                <b-button class="is-primary" icon-pack="fa" icon-left="search" @click="loadAsyncData"></b-button>
                            </p>
                        </b-field>

                        <div class="table-container">
                            <b-table
                                :data='data'
                                :loading="loading"
                                paginated
                                backend-pagination
                                :total='total'
                                :per-page="perPage"
                                @page-change="onPageChange"
                                detail-transition=""
                                aria-next-label="Next page"
                                aria-previous-label="Previouse page"
                                aria-page-label="Page"
                                :show-detail-icon=true
                                aria-current-label="Current page"
                                default-sort-direction="defualtSortDirection"
                                @sort="onSort">

                                <b-table-column field="course_id" label="ID" v-slot="props">
                                    {{props.row.course_id}}
                                </b-table-column>

                                <b-table-column field="course_code" label="Course Code" v-slot="props">
                                    {{props.row.course_code}}
                                </b-table-column>

                                <b-table-column field="course_desc" label="Course Desc" v-slot="props">
                                    {{props.row.course_desc}}
                                </b-table-column>

                                <b-table-column field="course_type" label="Course Type" v-slot="props">
                                    {{props.row.course_type}}
                                </b-table-column>

                                <b-table-column field="" label="Action" v-slot="props">
                                    <div class="buttons">
                                        <b-button class="is-small is-warning" @click="selectData(props.row)">
                                            <i class="fa fa-pencil"></i>&nbsp;&nbsp;SELECT</b-button>
                                    </div>
                                </b-table-column>
                            </b-table>
                        </div>

                    </div>
                </section>

                <footer class="modal-card-foot">
                    <b-button
                        label="Close"
                        @click="isModalActive=false"></b-button>
                </footer>
            </div>
        </b-modal>


    </div>
</template>

<script>
export default {
    props: {
        propCourse: {
            type: String,
            default: '',
        },
       
    },
    data(){
        return{
            data: [],
            total: 0,
            loading: false,
            sortfield: 'course_id',
            sortOrder:'desc',
            page: 1,
            perPage: 5,
            defaultSortDirection:'',

            isModalActive: false,
            errors:{},

 
            search: {
                courseCode: '',
                courseDesc: '',
            },


        }
    },
    methods: {
        loadAsyncData() {
            const params = [
                `sort_by=${this.sortfield}.${this.sortOrder}`,
                `perpage=${this.perPage}`,
                `page=${this.page}`,
                `coursecode=${this.search.courseCode}`,
                `coursedesc=${this.search.courseDesc}`,
            ].join('&');

            this.loading = true;
            axios.get(`/cpanel/get-browse-courses?${params}`).then(({data}) => {
                this.data = [];
                let currentTotal = data.total;
                if (data.total / this.perPage > 1000) {
                    currentTotal = this.perPage * 1000;
                }

                this.total = currentTotal;
                data.data.forEach((item) => {
                    this.data.push(item);
                });

                this.loading = false;
            }).catch(err => {
                this.data = [];
                this.total = 0;
                this.loading = false;
                throw err;
            });

        },

        onPageChange(page) {
            this.page = page;
            this.loadAsyncData();
        },

        onSort(field, order) {
            this.sortfield = field;
            this.sortOrder = order;
            this.loadAsyncData();
        },

        setPerPage() {
            this.loadAsyncData();
        },

        openModal(){
             this.loadAsyncData();
             this.isModalActive = true;
        },

        selectData(dataRow){
            this.isModalActive = false;
            this.$emit('browseCourses', dataRow);
        }


    },

    computed: {
        valueCourse(){
            return this.propCourse;
        }
    },

}
</script>

<style scoped>
    .card-width{
        width: 640px;
    }
</style>
