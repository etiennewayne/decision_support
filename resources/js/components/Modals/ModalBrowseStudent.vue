<template>
    <div>
        <b-field label="Select Student"
                 :type="this.errors.student_id ? 'is-danger':''"
                 :message="this.errors.student_id ? this.errors.student_id[0] : ''">

            <b-input :value="valueFullname" expanded
                     icon="account" placeholder="Select Student" required readonly>
            </b-input>

            <p class="control">
                <b-button class="button is-primary" @click="openModal">...</b-button>
            </p>
        </b-field>


        <b-modal v-model="isModalActive" has-modal-card
                 trap-focus scroll="keep">

            <div class="modal-card" style="width: 800px;">
                <header class="modal-card-head">
                    <p class="modal-card-title">Select Student</p>
                    <button type="button" class="delete"
                            @click="isModalActive = false"/>
                </header>

                <section class="modal-card-body">
                    <div>

                        <b-field label="Search" label-position="on-border" >
                            <b-input type="text" v-model="search.lname" placeholder="Search Lastname..." expanded auto-focus></b-input>
                            <b-input type="text" v-model="search.fname" placeholder="Search Firstname..." expanded auto-focus></b-input>
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

                                <b-table-column field="student_ref" label="Student Ref" v-slot="props">
                                    {{props.row.student_ref}}
                                </b-table-column>

                                <b-table-column field="lname" label="Lastname" v-slot="props">
                                    {{props.row.lname}}
                                </b-table-column>

                                <b-table-column field="fname" label="Firstname" v-slot="props">
                                    {{props.row.fname}}
                                </b-table-column>

                                <b-table-column field="mname" label="Middlename" v-slot="props">
                                    {{props.row.mname}}
                                </b-table-column>

                                <b-table-column field="mname" label="Middlename" v-slot="props">
                                    {{props.row.program.program_code}}
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
        propStudent: {
            type: String,
            default: '',
        },
    },
    data(){
        return{
            data: [],
            total: 0,
            loading: false,
            sortfield: 'student_id',
            sortOrder:'desc',
            page: 1,
            perPage: 5,
            defaultSortDirection:'',

            isModalActive: false,
            errors:{},

            student: {
                fullname: '',
            },
            search: {
                lname: '',
                fname: '',
            },


        }
    },
    methods: {
        loadAsyncData() {
            const params = [
                `sort_by=${this.sortfield}.${this.sortOrder}`,
                `perpage=${this.perPage}`,
                `page=${this.page}`,
                `lname=${this.search.lname}`,
                `fname=${this.search.fname}`,
            ].join('&');

            this.loading = true;
            axios.get(`/cpanel/get-students?${params}`).then(({data}) => {
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
            this.$emit('browseStudent', dataRow);
        }


    },

    computed: {
        valueFullname(){
            return this.propStudent;
        }
    },

}
</script>

<style scoped>
.card-width{
    width: 640px;
}
</style>
