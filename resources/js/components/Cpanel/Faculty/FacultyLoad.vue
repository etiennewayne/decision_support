<template>
    <div>
        <div class="section filter">

            <div class="columns is-centered">
                <div class="column is-8">
                    <div class="box">
                        <div class="table-title">
                            LIST OF FACULTY LOAD
                        </div>

                        <div class="columns">
                            <div class="column">
                                <b-field label="Academic Year">
                                    <b-select v-model="fields.ay">
                                        <option v-for="(item, index) in acadYears" :key="index" :value="item.acadyear_id">{{ item.code }} ({{ item.acadyear_desc }})</option>
                                    </b-select>
                                </b-field>
                            </div>

                        </div>
                        <div class="columns">
                            <div class="column">
                                <modal-browse-faculty
                                    :prop-faculty="facultyFullName"
                                    @browseFaculty="emitBrowseFaculty">
                                </modal-browse-faculty>
                            </div>
                        </div>

                        <div class="buttons">
                            <b-button label="Generate Faculty Load" type="is-info" @click="loadFacultySchedules"></b-button>
                        </div>
                    </div><!--box -->
                </div><!--col -->

            </div><!-- cols -->
        </div><!--section div-->

        <div class="print-section">
            <div style="font-weight: bold; margin: 10px auto; text-align: center;">FACULTY LOAD</div>

            <div v-if="data.length > 0" class="print-body">

                <div style="margin: 15px 0;">
                    <div class="has-text-weight-bold">INSTRUCTOR: {{ data[0].lname }}, {{ data[0].fname }} {{ data[0].mname }}</div>
                    <div class="has-text-weight-bold">SCHOOL YEAR: {{ data[0].acadyear_desc }}</div>
                </div>

                <div style="margin: 15px 0;">
                    <div class="has-text-weight-bold">COURSES: </div>
                </div>
                <table class="table">
                    <tr v-for="(item, index) in data" :key="index">
                        <td>{{ item.course_code }}</td>
                        <td>{{ item.course_desc }}</td>
                        <td>{{ item.start_time | formatTime }}</td>
                        <td>{{ item.end_time | formatTime }}</td>
                        <td>{{ item.course_type }}</td>
                        <td>{{ item.course_unit }}</td>
                        <td>
                            <span v-if="item.mon">M</span>
                            <span v-if="item.tue">T</span>
                            <span v-if="item.wed">W</span>
                            <span v-if="item.thu">TH</span>
                            <span v-if="item.fri">F</span>
                            <span v-if="item.sat">SAT</span>
                            <span v-if="item.sun">SUN</span>
                        </td>

                    </tr>
                </table>
            </div>
        </div>

    </div>
</template>

<script>

export default{
    props: ['propAcadYears'],
    data() {

        return{
            data: [],
            faculty: {},
            acadYears: [],
            facultyFullName: '',

            fields: {},

            btnClass: {
                'is-success': true,
                'button': true,
                'is-loading':false,
            },
        }

    },

    methods: {

        loadAcadYear(){
            this.acadYears = JSON.parse(this.propAcadYears);
        },

        emitBrowseFaculty(d){
            let mname = '';
            if(d.mname){
                mname = d.mname;
            }

            this.facultyFullName  = d.lname + ', ' + d.fname + ' ' + mname;
            this.fields.fid = d.faculty_id;
        },

        loadFacultySchedules: function(){

            if(!this.fields.fid){
                this.$buefy.dialog.alert({
                    message: 'Please select faculty.'
                });
            }
            if(!this.fields.ay){
                this.$buefy.dialog.alert({
                    message: 'Please select academic year.'
                });
            }

            const params = [
                `fid=${this.fields.fid}`,
                `ayid=${this.fields.ay}`,
            ].join('&');

            axios.get(`/cpanel/get-faculty-load?${params}`).then(res=>{
                this.data = res.data
            })
        }


    },

    mounted() {
       this.loadAcadYear()
    }
}
</script>


<style scoped src="../../../../sass/faculty-load-print.css">
</style>
