<template>
    <div>
        <div class="section">
            <div class="columns is-centered">
                <div class="column is-8">
                    <div class="box">
                        <div>
                            SCHEDULE INFORMATION ENTRY
                        </div>

                        <div class="schedule-body">
                            <b-field label="Academic Year">
                                <b-select v-model="fields.code">
                                    <option v-for="(item, index) in acadYears" :key="index" :value="item.acadyear_id">{{ item.code }}</option>
                                </b-select>
                            </b-field>
                            <b-field label="Programs">
                                <b-select v-model="fields.program">
                                    <option v-for="(item, index) in programs" :key="index" :value="item.program_id">{{ item.program_code }}</option>
                                </b-select>
                            </b-field>
                            <b-field>
                                <modal-courses :propCourse="fields.course_desc" @browseCourses="emitBrowseCourse($event)"></modal-courses>
                            </b-field>
                            


                            <b-field label="Time">
                                <b-timepicker placeholder="From"></b-timepicker>
                                <b-timepicker placeholder="To"></b-timepicker>
                            </b-field>

                            <b-field label="Day">
                                <b-checkbox>
                                    Mon
                                </b-checkbox>
                                <b-checkbox>
                                    Tue
                                </b-checkbox>
                                <b-checkbox>
                                    Wed
                                </b-checkbox>
                                <b-checkbox>
                                    Thu
                                </b-checkbox>
                                <b-checkbox>
                                    Fri
                                </b-checkbox>
                                <b-checkbox>
                                    Sat
                                </b-checkbox>
                                <b-checkbox>
                                    Sun
                                </b-checkbox>
                            </b-field>

                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div><!--root div-->
</template>

<script>

export default {
    props: ['propAcadYears', 'propPrograms'],

    data(){
        return{
            fields: {
                course_id: 0,
                course_code: '',
                course_desc: '',
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

</style>