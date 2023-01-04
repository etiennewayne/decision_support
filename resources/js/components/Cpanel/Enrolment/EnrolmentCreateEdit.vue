<template>
    <div>
        <div class="section">
            <div class="columns is-centered">
                <div class="column is-6">
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

               

                                <div class="buttons mt-4 is-right">
                                    <button class="button is-primary">SAVE</button>
                                </div>
                            </div>
                        </div> <!-- box -->
                    </form> <!-- form-->
                </div> <!--column -->
            </div><!-- cols -->

        </div><!--section-->

        <!--modal create-->
        <b-modal v-model="modalConflict" has-modal-card
             trap-focus
             :width="640"
             aria-role="dialog"
             aria-label="Modal"
             aria-modal>


            <div class="modal-card">
                <header class="modal-card-head">
                    <p class="modal-card-title">CONFLICT(S)</p>
                    <button
                        type="button"
                        class="delete"
                        @click="modalConflict = false" />
                </header>

                <section class="modal-card-body">
                    <div class="">
                        <div class="columns">
                            <div class="column">

                               <table class="table">
                                   <tr>
                                       <th>ID</th>
                                       <th>Course Code</th>
                                       <th>Description</th>
                                       <th>Time & Day</th>
                                       <th>Day</th>
                                       <th>Room</th>
                                   </tr>
                                   <tr v-for="(item, index) in conflictData" :key="index">
                                       <td>{{ item.schedule_id }}</td>
                                       <td>{{ item.course.course_code }}</td>
                                       <td>{{ item.course.course_desc }}</td>
                                       <td>{{ item.start_time | formatTime }} - {{ item.end_time | formatTime }}</td>
                                       <td>
                                           <span class="days" v-if="item.mon">M</span>
                                           <span class="days" v-if="item.tue">T</span>
                                           <span class="days" v-if="item.wed">W</span>
                                           <span class="days" v-if="item.thu">TH</span>
                                           <span class="days" v-if="item.fri">F</span>
                                           <span class="days" v-if="item.sat">SAT</span>
                                           <span class="days" v-if="item.sun">SUN</span>
                                       </td>
                                       <td>{{ item.room.room }}</td>
                                   </tr>
                               </table>


                            </div>
                        </div>
                    </div>
                </section>
                <footer class="modal-card-foot">
                    <b-button
                        label="Close"
                        @click="modalConflict=false"/>
                </footer>
            </div>

        </b-modal>
        <!--close modal-->

    </div><!--root div-->
</template>

<script>
import axios from 'axios';


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
            rooms: [],

            conflictData: {},
            modalConflict: false,

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
        },

        submit: function(){

            if(this.global_id > 0){
                //update
                axios.put('/cpanel/schedules/' + this.global_id, this.fields).then(res=>{
                //console.log(res.data);
                    if(res.data.status === 'updated'){
                        this.$buefy.dialog.alert({
                            title: 'UPDATED!',
                            message: 'Successfully updated.',
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
            }else{
                //insert
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

                        if(this.errors.schedule[1]){
                            this.conflictData = JSON.parse(this.errors.schedule[1]);
                            console.log(this.conflictData);
                            this.modalConflict = true;
                        }
                    }
                })
            }
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
