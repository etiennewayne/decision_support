<template>
    <div>
        <div class="section">
            <div class="columns is-centered">
                <div class="column is-6">
                    <form @submit.prevent="submit">
                    
                        <div class="box">
                            <div class="subtitle">
                                SCHEDULE INFORMATION ENTRY
                            </div>

                            <div class="schedule-body">
                                <b-field label="Academic Year"
                                    :type="this.errors.acadyear_id ? 'is-danger':''"
                                    :message="this.errors.acadyear_id ? this.errors.acadyear_id[0] : ''">
                                    <b-select v-model="fields.acadyear_id">
                                        <option v-for="(item, index) in acadYears" :key="index" :value="item.acadyear_id">{{ item.code }}</option>
                                    </b-select>
                                </b-field>
                                <b-field label="Programs"
                                    :type="this.errors.program_id ? 'is-danger':''"
                                    :message="this.errors.program_id ? this.errors.program_id[0] : ''">
                                    <b-select v-model="fields.program_id">
                                        <option v-for="(item, index) in programs" :key="index" :value="item.program_id">{{ item.program_code }}</option>
                                    </b-select>
                                </b-field>
                                <b-field
                                    :type="this.errors.course_id ? 'is-danger':''"
                                    :message="this.errors.course_id ? this.errors.course_id[0] : ''">
                                    <modal-courses :propCourse="fields.course_desc" @browseCourses="emitBrowseCourse($event)"></modal-courses>
                                </b-field>
                                
                                <b-field label="Time">
                                    <b-field label-position="on-border" label="Start time"
                                        :type="this.errors.start_time ? 'is-danger':''"
                                        :message="this.errors.start_time ? this.errors.start_time[0] : ''"
                                    >
                                        <b-timepicker v-model="fields.start_time" placeholder="From" inline></b-timepicker>
                                    </b-field>
                                    <b-field label-position="on-border" label="End time"
                                        :type="this.errors.end_time ? 'is-danger':''"
                                        :message="this.errors.end_time ? this.errors.end_time[0] : ''"
                                    >
                                        <b-timepicker v-model="fields.end_time" placeholder="To" inline></b-timepicker>
                                    </b-field>
                                   
                                </b-field>

                                <b-field label="Day">
                                    <div class="day-container">
                                        <b-checkbox class="checkbox" v-model="fields.mon">Mon</b-checkbox>
                                        <b-checkbox v-model="fields.tue">Tue</b-checkbox>
                                        <b-checkbox v-model="fields.wed">Wed</b-checkbox>
                                        <b-checkbox v-model="fields.thu">Thur</b-checkbox>
                                        <b-checkbox v-model="fields.fri">Fri</b-checkbox>
                                        <b-checkbox v-model="fields.sat">Sat</b-checkbox>
                                        <b-checkbox v-model="fields.sun">Sun</b-checkbox>
                                    </div>
                                </b-field>

                                <div class="buttons mt-4 is-right">
                                    <button class="button is-primary">SAVE</button>
                                </div>
                            </div>
                        </div> <!-- box -->
                    </form> <!-- form-->
                </div> <!--column -->
            </div><!-- cols -->
            
        </div>
    </div><!--root div-->
</template>

<script>
import axios from 'axios';


export default {
    props: ['propAcadYears', 'propPrograms'],

    data(){
        return{
            fields: {
                course_id: null,
                start_time: null,
                end_time: null,
                mon: false,
                tue: false,
                wed: false,
                thu: false,
                fri: false,
                sat: false,
                sun: false
            },
            errors: {},

            acadYears: [],
            programs: [],

        }
    },

    methods: {

        initData: function(){
            this.acadYears = JSON.parse(this.propAcadYears);
            this.programs = JSON.parse(this.propPrograms);
        },

        emitBrowseCourse: function(rowData){
            console.log(rowData);


            this.fields.course_desc = rowData.course_desc;
            this.fields.course_id = rowData.course_id;
        },

        submit: function(){


            // this.fields.mon = field.mon === 1 ? true : false;
            // this.fields.tue = res.data.tue === 1 ? true : false;
            // this.fields.wed = res.data.wed === 1 ? true : false;
            // this.fields.thur = res.data.thur === 1 ? true : false;
            // this.fields.fri = res.data.fri === 1 ? true : false;
            // this.fields.sat = res.data.sat === 1 ? true : false;
            // this.fields.sun = res.data.sun === 1 ? true : false;

            axios.post('/cpanel/schedules', this.fields).then(res=>{
                //console.log(res.data);
                if(res.data.status === 'saved'){
                    this.$buefy.dialog.alert({
                        title: 'SAVED!',
                        message: 'Successfully saved.',
                        type: 'is-success',
                        onConfirm: () => {
                            window.location = '/cpanel/schedules';
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