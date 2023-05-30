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
                                        <option v-for="(item, index) in acadYears" :key="index" 
                                            :value="item.acadyear_id">{{ item.code }} ({{ item.acadyear_desc }})</option>
                                    </b-select>
                                </b-field>
                            </div>
                        </div>

                        <div class="buttons">
                            <b-button label="Generate Faculty Load" type="is-info" @click="loadFacultySchedules"></b-button>
                            <b-button label="Print" type="is-info" icon-left="printer" @click="printMe()"></b-button>
                        </div>

                    </div><!--box -->
                </div><!--col -->

            </div><!-- cols -->
        </div><!--section div-->

        <div class="print-section">
            <div style="font-weight: bold; margin: 10px auto; text-align: center;">FACULTY LOAD</div>

            <div v-if="data.length > 0" class="print-body">

                <div v-for="(item, index) in data" :key="index">

                    <div style="margin: 15px 0;">
                        <div class="has-text-weight-bold">INSTRUCTOR: {{ item.lname }}, {{ item.fname }} {{ item.mname }}</div>
                        <div v-if="item.schedules" class="has-text-weight-bold">SCHOOL YEAR: {{ item.schedules[0].acadyear.acadyear_desc }}</div>
                    </div>

                    <table class="report-table">

                        <tr v-for="(i,ix) in item.schedules" :key="`item${ix}`">
                            <td>{{ i.course.course_code }} ({{ i.course.course_type }})</td>
                            <td>{{ i.course.course_desc }}</td>
                            <td>{{ i.start_time | formatTime }} - {{ i.end_time | formatTime }}</td>
                            <td>{{ i.course.course_unit }}</td>
                            <td>
                                <span v-if="i.mon">M</span>
                                <span v-if="i.tue">T</span>
                                <span v-if="i.wed">W</span>
                                <span v-if="i.thu">TH</span>
                                <span v-if="i.fri">F</span>
                                <span v-if="i.sat">SAT</span>
                                <span v-if="i.sun">SUN</span>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;" colspan="5">Total Units: {{ totalUnits(item) }}</td>
                        </tr>
                    </table>

                    <hr>
                </div>
              
                
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
            acadYears: [],
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

        loadFacultySchedules: function(){

            if(!this.fields.ay){
                this.$buefy.dialog.alert({
                    message: 'Please select academic year.'
                });
            }

            const params = [
                `ayid=${this.fields.ay}`,
            ].join('&');

            axios.get(`/cpanel/get-all-faculty-load?${params}`).then(res=>{
                this.data = res.data
            })
        },

        printMe(){
            window.print()
        },
        totalUnits(i){
            //return i;
            
            let ntotal = 0;
            i.schedules.forEach(el =>{
                ntotal += el.course.course_unit
            })
            return ntotal;
        }
       
    },

    mounted() {
       this.loadAcadYear()
    },

    computed: {
        
    }
}
</script>


<style scoped src="../../../../sass/faculty-load-print.css">


</style>
