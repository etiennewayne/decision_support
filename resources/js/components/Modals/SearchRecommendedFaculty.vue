<template>

    <div>
        <b-tooltip label="Faculty Options" type="is-primary">
            <b-dropdown aria-role="list">
                <template #trigger="{ active }">
                    <b-button
                        label="Option"
                        type="is-info is-small"
                        :icon-right="active ? 'menu-up' : 'menu-down'">
                    </b-button>
                </template>

                <b-dropdown-item aria-role="listitem" @click="openModal">Assign Faculty</b-dropdown-item>
                <b-dropdown-item aria-role="listitem" @click="removeFaculty">Remove Faculty</b-dropdown-item>


            </b-dropdown>
        </b-tooltip>



        <b-modal v-model="this.modalAssignFaculty" has-modal-card
                 trap-focus scroll="keep" aria-role="dialog" aria-modal>
            <div class="modal-card card-width">
                <header class="modal-card-head">
                    <p class="modal-card-title">SELECT FACULTY</p>
                    <button type="button" class="delete"
                            @click="modalAssignFaculty = false" />

                </header>

                <section class="modal-card-body">
                    <div>
                        <b-field label="Search Option" label-position="on-border">
                            <b-select v-model="isLoadAll">
                                <option value="0">Load Recommended Faculty</option>
                                <option value="1">Load All Faculty</option>
                            </b-select>

                        </b-field>
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

                                <b-table-column field="faculty_id" label="ID" v-slot="props">
                                    {{props.row.faculty_id}}
                                </b-table-column>

                                <b-table-column field="name" label="Name" v-slot="props">
                                    {{props.row.lname}}, {{ props.row.fname }} {{ props.row.mname }}
                                </b-table-column>

                                <b-table-column field="sex" label="Sex" v-slot="props">
                                    {{props.row.sex}}
                                </b-table-column>

                                <b-table-column field="count_teaching" label="No. of Teaching" centered v-slot="props">
                                    {{props.row.count_teaching}}
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

                    <div class="faculty-selected">
                        <div class="faculty-title">Faculty Selected</div>
                        <div v-if="this.facultySelected">
                            <span>{{ this.facultySelected.lname }}, {{ this.facultySelected.fname }} {{ this.facultySelected.mname }}</span>
                            <b-button type="is-danger" @click="removeSelectedFaculty" class="is-small">X</b-button>
                        </div>
                        <div v-if="this.errors.faculty_id">
                            <span
                                style="font-weight: bold; margin-top: 5px; color: red; font-style: italic;">
                                {{ this.errors.faculty_id[0] }}
                            </span>
                        </div>
                    </div>
                </section>

                <footer class="modal-card-foot">
                    <b-button
                        label="Close"
                        @click="modalAssignFaculty=false"></b-button>
                    <b-button
                        type="is-success"
                        label="Save Faculty"
                        @click="saveFaculty"></b-button>
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
        propCourseId: {
            type: Number,
            default: 0
        },
        propScheduleId: {
            type: Number,
            default: 0
        }
    },

    data() {
        return{
            data: [],
            total: 0,
            loading: false,
            sortfield: 'course_id',
            sortOrder:'desc',
            page: 1,
            perPage: 5,
            defaultSortDirection:'',

            search: '',
            modalAssignFaculty: false,

            isLoadAll: 0,

            facultySelected: {},
            errors: {},
        }
    },

    methods: {
        assignFaculty(){

        },

        loadAsyncData() {
            const params = [
                `sort_by=${this.sortfield}.${this.sortOrder}`,
                `perpage=${this.perPage}`,
                `page=${this.page}`,
                `courseid=${this.propCourseId}`,
                `coursedesc=${this.search.courseDesc}`,
                `isloadall=${this.isLoadAll}`,
            ].join('&');

            this.loading = true;
            axios.get(`/cpanel/get-recommended-faculty?${params}`).then(({data}) => {
                this.data = [];
                let currentTotal = data.total;
                if (data.total / this.perPage > 1000) {
                    currentTotal = this.perPage * 1000;
                }

                this.total = currentTotal;
                data.forEach((item) => {
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
            this.modalAssignFaculty = true;
        },

        selectData(dataRow){
           // this.modalAssignFaculty = false;
            //this.$emit('browseRecommendedFaculty', dataRow);
            this.facultySelected = dataRow;
            console.log(dataRow);
        },

        refreshParent(){
            this.$emit('refresh')
        },
        removeSelectedFaculty(){
            this.facultySelected = {};

        },

        saveFaculty(){
            this.errors = {};

            axios.post('/cpanel/save-faculty', {
                faculty_id: this.facultySelected.faculty_id,
                schedule_id: this.propScheduleId,
            }).then(res=>{
                if(res.data.status === 'saved'){
                    this.modalAssignFaculty = false;
                    // this.$buefy.toast({
                    //     title: 'SAVED!',
                    //     message: 'Faculty saved successfully.',
                    //     type: 'is-success'
                    // })

                    this.$buefy.dialog.alert({
                        title: 'SAVED?',
                        type: 'is-success',
                        message: 'Faculty saved successfully.',
                        confirmText: 'Ok',
                        onConfirm: ()=> {
                            this.refreshParent()
                        }
                    });


                }
            }).catch(err=>{
                //console.log(err.response.status)
                if(err.response.status === 422){
                    this.errors = err.response.data.errors;
                    //console.log(this.errors);
                }
            })
        },

        removeFaculty(){
            axios.post('/cpanel/remove-faculty/'+ this.propScheduleId).then(res=>{
                if(res.data.status === 'removed'){
                    this.$buefy.dialog.alert({
                        title: 'REMOVED?',
                        type: 'is-success',
                        message: 'Faculty removed successfully.',
                        confirmText: 'Ok',
                        onConfirm: ()=> {
                            this.refreshParent()
                        }
                    });

                }
            })
        }
    }
}
</script>

<style scoped>

.faculty-selected{
    margin: 15px;
}

.faculty-title{
    font-weight: bold;
    font-size: 1.3em;
}
</style>
