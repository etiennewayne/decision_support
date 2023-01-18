<template>
    <div>
        <div class="section">
            <div class="columns is-centered">
                <div class="column is-10">
                    <form @submit.prevent="submit">

                        <div class="box">
                            <div class="subtitle">
                                STUDENT ENROLMENT ENTRY
                            </div>

                            <div class="schedule-body">
                                <b-field label="Academic Year"
                                    :type="this.errors.acadyear_id ? 'is-danger':''"
                                    :message="this.errors.acadyear_id ? this.errors.acadyear_id[0] : ''">
                                    <b-select v-model="fields.acadyear_id">
                                        <option v-for="(item, index) in acadYears" :key="index" :value="item.acadyear_id">{{ item.code }}</option>
                                    </b-select>
                                </b-field>

                                <modal-browse-student
                                    :prop-student="fullname"
                                    @browseStudent="emitBrowseStudent($event)"></modal-browse-student>

                                <hr>

                                <div style="font-weight: bold;">Schedules</div>
                                <div>
                                    <table class="table is-fullwidth">
                                        <tr>
                                            <th>Schedule Id</th>
                                            <th>Course Code</th>
                                            <th>Course Desc</th>
                                            <th>Time</th>
                                            <th>Day</th>
                                            <th>Action</th>
                                        </tr>
                                        <tr v-for="(item, index) in fields.enrolment_details" :key="index">
                                            <td>{{ item.schedule_id}}</td>
                                            <td>{{ item.course_code}}</td>
                                            <td>{{ item.course_desc}}</td>
                                            <td>{{ item.schedule_time}}</td>
                                            <td>
                                                <span class="days" v-if="item.mon">M</span>
                                                <span class="days" v-if="item.tue">T</span>
                                                <span class="days" v-if="item.wed">W</span>
                                                <span class="days" v-if="item.thu">TH</span>
                                                <span class="days" v-if="item.fri">F</span>
                                                <span class="days" v-if="item.sat">SAT</span>
                                                <span class="days" v-if="item.sun">SUN</span>
                                            </td>
                                            <td>
                                                <b-button type="is-info" class="is-small is-danger mt-5" icon-right="trash" @click="removeSchedule(index)">Remove</b-button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <div class="buttons">
                                                <modal-browse-schedule-enrolment class="mt-5" :prop-ay-id="fields.acadyear_id"
                                                    @browseSchedule="emitBrowseSchedule($event)"></modal-browse-schedule-enrolment>
<!--                                                <b-button type="is-info" class="is-small mt-5" @click="addSchedule">Add</b-button>-->
                                            </div>
                                        </tr>
                                    </table>
                                </div>

                                <div class="buttons mt-4 is-right">
                                    <b-button class="button is-primary" @click="submit">SAVE</b-button>
                                </div>
                            </div>
                        </div> <!-- box -->
                    </form> <!-- form-->
                </div> <!--column -->
            </div><!-- cols -->

        </div><!--section-->

    </div><!--root div-->
</template>

<script>

export default {

    props: ['propAcadYears', 'propPrograms', 'propData'],

    data(){
        return{
            global_id: 0, //for edit or create reference

            fields: {
                acadyear_id: null,
                student_id: null,
                program_id: null,
                enrolment_details: [],
            },
            errors: {},

            acadYears: [],
            programs: [],

            fullname: '',


        }
    },

    methods: {

        initData: function(){

            this.acadYears = JSON.parse(this.propAcadYears);
            this.programs = JSON.parse(this.propPrograms);
        },

        emitBrowseStudent: function(rowData){
            console.log(rowData);
            this.fullname = rowData.lname + ', ' + rowData.fname + ' ' + rowData.mname
            this.fields.student_id = rowData.student_id;
            this.fields.program_id = rowData.program_id;
        },

        emitBrowseSchedule: function(rowData){
            console.log(rowData);

            if(this.fields.program_id !== rowData.program_id){
                this.$buefy.dialog.confirm({
                    title: 'Continue?',
                    type: 'is-info',
                    message: 'Course program is different from the course of the student. Do you want to continue to load this course?',
                    cancelText: 'Cancel',
                    confirmText: 'Continue',
                    onConfirm: () => {
                        this.fields.enrolment_details.push({
                            schedule_id: rowData.schedule_id,
                            course_code: rowData.course.course_code,
                            course_desc: rowData.course.course_desc,
                            schedule_time: rowData.start_time + " - " + rowData.end_time,
                            mon: rowData.mon,
                            tue: rowData.tue,
                            wed: rowData.wed,
                            thu: rowData.thu,
                            fri: rowData.fri,
                            sat: rowData.sat,
                            sun: rowData.sun,
                        });
                    }
                });
            }else{
                this.fields.enrolment_details.push({
                    schedule_id: rowData.schedule_id,
                    course_code: rowData.course.course_code,
                    course_desc: rowData.course.course_desc,
                    schedule_time: rowData.start_time + " - " + rowData.end_time,
                    mon: rowData.mon,
                    tue: rowData.tue,
                    wed: rowData.wed,
                    thu: rowData.thu,
                    fri: rowData.fri,
                    sat: rowData.sat,
                    sun: rowData.sun,
                });
            }




        },

        submit: function(){

            if(this.global_id > 0){
                //update
                axios.put('/cpanel/enrolment/' + this.global_id, this.fields).then(res=>{
                //console.log(res.data);
                    if(res.data.status === 'updated'){
                        this.$buefy.dialog.alert({
                            title: 'UPDATED!',
                            message: 'Successfully updated.',
                            type: 'is-success',
                            onConfirm: () => {
                                window.location = '/cpanel/enrolment';
                            }
                        })
                    }
                }).catch(err=>{
                    //console.log(err.response.status)
                    if(err.response.status === 422){
                        this.errors = err.response.data.errors;
                        console.log(this.errors);
                    }
                })
            }else{
                //insert
                axios.post('/cpanel/enrolment', this.fields).then(res=>{
                //console.log(res.data);
                    if(res.data.status === 'saved'){
                        this.$buefy.dialog.alert({
                            title: 'SAVED!',
                            message: 'Successfully saved.',
                            type: 'is-success',
                            onConfirm: () => {
                                window.location = '/cpanel/enrolment';
                            }
                        })
                    }
                }).catch(err=>{
                    //console.log(err.response.status)
                    if(err.response.status === 422){
                        this.errors = err.response.data.errors;
                        console.log(this.errors);

                        if(this.errors.schedule[1]){
                            this.conflictData = JSON.parse(this.errors.schedule[1]);
                            console.log(this.conflictData);
                            this.modalConflict = true;
                        }
                    }
                })
            }
        },

        removeSchedule(index){
            this.fields.enrolment_details.splice(index, 1);
        }

    },

    mounted(){
        this.initData();
    }
}
</script>

<style scoped>
    .schedule-body{
        margin: 15px 0 0 0;
    }

    .day-container{
        display: flex;
        flex-wrap: wrap;
        justify-content: space-around;
    }

    @media screen and (max-width: 620px) {
        /* .day-container {
            flex-direction: column;
        } */


    }


</style>
