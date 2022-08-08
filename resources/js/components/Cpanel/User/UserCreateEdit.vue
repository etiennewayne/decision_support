<template>

    <div>
        <div class="section">

            <div class="columns is-centered">
                <div class="column is-8">
                    <form @submit.prevent="submit">

                        <div class="box">
                            <div class="table-title">
                                USER REGISTRATION
                            </div>
                            <div class="">
                                <div class="columns">
                                    <div class="column">
                                        <b-field label="Username" label-position="on-border"
                                                 :type="this.errors.username ? 'is-danger':''"
                                                 :message="this.errors.username ? this.errors.username[0] : ''">
                                            <b-input v-model="fields.username"
                                                     placeholder="Username" required>
                                            </b-input>
                                        </b-field>
                                    </div>
                                </div>

                                <div class="columns">
                                    <div class="column">
                                        <b-field label="Last Name" label-position="on-border"
                                                 :type="this.errors.lname ? 'is-danger':''"
                                                 :message="this.errors.lname ? this.errors.lname[0] : ''">
                                            <b-input v-model="fields.lname"
                                                     placeholder="Last Name" required>
                                            </b-input>
                                        </b-field>
                                    </div>
                                    <div class="column">
                                        <b-field label="First Name" label-position="on-border"
                                                 :type="this.errors.fname ? 'is-danger':''"
                                                 :message="this.errors.fname ? this.errors.fname[0] : ''">
                                            <b-input v-model="fields.fname"
                                                     placeholder="First Name" required>
                                            </b-input>
                                        </b-field>
                                    </div>
                                </div>

                                <div class="columns">
                                    <div class="column">
                                        <b-field label="Middle Name" label-position="on-border"
                                                 :type="this.errors.mname ? 'is-danger':''"
                                                 :message="this.errors.mname ? this.errors.mname[0] : ''">
                                            <b-input v-model="fields.mname"
                                                     placeholder="Middle Name">
                                            </b-input>
                                        </b-field>
                                    </div>

                                    <div class="column">
                                        <b-field label="Suffix" label-position="on-border"
                                                 :type="this.errors.suffix ? 'is-danger':''"
                                                 :message="this.errors.suffix ? this.errors.suffix[0] : ''">
                                            <b-input type="text" v-model="fields.suffix"
                                                     placeholder="Suffix" required>
                                            </b-input>
                                        </b-field>
                                    </div>

                                </div>

                                <div class="columns">
                                    <div class="column">
                                        <b-field label="Contact No" label-position="on-border"
                                                 :type="this.errors.contact_no ? 'is-danger':''"
                                                 :message="this.errors.contact_no ? this.errors.contact_no[0] : ''">
                                            <b-input type="number" v-model="fields.contact_no"
                                                     placeholder="Contact No" required>
                                            </b-input>
                                        </b-field>
                                    </div>
                                    <div class="column">
                                        <b-field label="Email" label-position="on-border"
                                                 :type="this.errors.email ? 'is-danger':''"
                                                 :message="this.errors.email ? this.errors.email[0] : ''">
                                            <b-input type="email" v-model="fields.email"
                                                     placeholder="Email" required>
                                            </b-input>
                                        </b-field>
                                    </div>
                                </div>
                                <div class="columns" v-if="global_id < 1">
                                    <div class="column">
                                        <b-field label="Password" label-position="on-border"
                                                 :type="this.errors.password ? 'is-danger':''"
                                                 :message="this.errors.password ? this.errors.password[0] : ''">
                                            <b-input type="password" password-reveal v-model="fields.password"
                                                     placeholder="Password" required>
                                            </b-input>
                                        </b-field>
                                    </div>
                                    <div class="column">
                                        <b-field label="Confirm Password" label-position="on-border"
                                                 :type="this.errors.password_confirmation ? 'is-danger':''"
                                                 :message="this.errors.password_confirmation ? this.errors.password_confirmation[0] : ''">
                                            <b-input type="password" password-reveal v-model="fields.password_confirmation"
                                                     placeholder="Confirm Password" required>
                                            </b-input>
                                        </b-field>
                                    </div>
                                </div>


                                <div class="columns">
                                    <div class="column">
                                        <b-field label="Sex" label-position="on-border" expanded
                                                 :type="this.errors.sex ? 'is-danger':''"
                                                 :message="this.errors.sex ? this.errors.sex[0] : ''"
                                        >
                                            <b-select v-model="fields.sex" expanded>
                                                <option value="MALE">MALE</option>
                                                <option value="FEMALE">FEMALE</option>
                                            </b-select>
                                        </b-field>
                                    </div>

                                    <div class="column">
                                        <b-field label="Programs" label-position="on-border" expanded
                                                 :type="this.errors.program_id ? 'is-danger':''"
                                                 :message="this.errors.program_id ? this.errors.program_id[0] : ''">
                                            <b-select v-model="fields.program_id" expanded>
                                                <option v-for="(item, index) in programs" :key="index" :value="item.program_id">{{ item.program_code }} - {{ item.program_desc }}</option>
                                            </b-select>
                                        </b-field>
                                    </div>
                                </div>

                                <div class="columns">
                                    <div class="column">
                                        <b-field label="Last School Attended" label-position="on-border"
                                                 :type="this.errors.last_school_attended ? 'is-danger':''"
                                                 :message="this.errors.last_school_attended ? this.errors.last_school_attended[0] : ''">
                                            <b-input type="text" v-model="fields.last_school_attended"
                                                     placeholder="Last School Attended" required>
                                            </b-input>
                                        </b-field>
                                    </div>
                                </div>

                                <div class="columns">
                                    <div class="column">
                                        <b-field label="Role" label-position="on-border" expanded
                                                 :type="this.errors.role ? 'is-danger':''"
                                                 :message="this.errors.role ? this.errors.role[0] : ''">
                                            <b-select v-model="fields.role" expanded>
                                                <option value="ADMIN">ADMINISTRATOR</option>
                                                <option value="STAFF">STAFF</option>
                                                <option value="STUDENT">STUDENT</option>
                                            </b-select>
                                        </b-field>
                                    </div>
                                </div>



                                <div class="columns">
                                    <div class="column">
                                        <b-field label="Province" label-position="on-border" expanded
                                                 :type="this.errors.province ? 'is-danger':''"
                                                 :message="this.errors.province ? this.errors.province[0] : ''">
                                            <b-select v-model="fields.province" @input="loadCity" expanded>
                                                <option v-for="(item, index) in provinces" :key="index" :value="item.provCode">{{ item.provDesc }}</option>
                                            </b-select>
                                        </b-field>
                                    </div>

                                    <div class="column">
                                        <b-field label="City" label-position="on-border" expanded
                                                 :type="this.errors.city ? 'is-danger':''"
                                                 :message="this.errors.city ? this.errors.city[0] : ''">
                                            <b-select v-model="fields.city" @input="loadBarangay" expanded>
                                                <option v-for="(item, index) in cities" :key="index" :value="item.citymunCode">{{ item.citymunDesc }}</option>
                                            </b-select>
                                        </b-field>
                                    </div>
                                </div>

                                <div class="columns">
                                    <div class="column">
                                        <b-field label="Barangay" label-position="on-border" expanded
                                                 :type="this.errors.barangay ? 'is-danger':''"
                                                 :message="this.errors.barangay ? this.errors.barangay[0] : ''">
                                            <b-select v-model="fields.barangay" expanded>
                                                <option v-for="(item, index) in barangays" :key="index" :value="item.brgyCode">{{ item.brgyDesc }}</option>
                                            </b-select>
                                        </b-field>
                                    </div>
                                    <div class="column">
                                        <b-field label="Street" label-position="on-border">
                                            <b-input v-model="fields.street"
                                                     placeholder="Street">
                                            </b-input>
                                        </b-field>
                                    </div>
                                </div>

                                <div class="buttons is-right">
                                    <button class="button is-primary">SAVE</button>
                                </div>
                            </div>
                        </div><!--box-->
                    </form><!--form -->
                </div><!--col-->
            </div><!--cols-->

        </div>
    </div><!--end page/ root div-->



</template>

<script>
export default {
    props: ['propPrograms', 'propId'],
    data(){
        return{
            errors: {},
            fields: {},

            provinces: [],
            cities: [],
            barangays: [],

            global_id: 0,

            programs: [],

        }
    },


    methods: {

        loadProvince: function(){
            axios.get('/load-provinces').then(res=>{
                this.provinces = res.data;
            })
        },

        loadCity: function(){
            axios.get('/load-cities?prov=' + this.fields.province).then(res=>{
                this.cities = res.data;
            })
        },

        loadBarangay: function(){
            axios.get('/load-barangays?prov=' + this.fields.province + '&city_code='+this.fields.city).then(res=>{
                this.barangays = res.data;
            })
        },


        submit: function(){
            if(this.global_id > 0){
                //update
                axios.put('/cpanel/users/'+this.global_id, this.fields).then(res=>{
                    if(res.data.status === 'updated'){
                        this.$buefy.dialog.alert({
                            title: 'UPDATED!',
                            message: 'Successfully updated.',
                            type: 'is-success',
                            onConfirm: () => {
                                this.loadAsyncData();
                                this.clearFields();
                                this.global_id = 0;
                                this.isModalCreate = false;
                            }
                        })
                    }
                }).catch(err=>{
                    if(err.response.status === 422){
                        this.errors = err.response.data.errors;
                    }
                })
            }else{
                //INSERT HERE
                axios.post('/cpanel/users', this.fields).then(res=>{
                    if(res.data.status === 'saved'){
                        this.$buefy.dialog.alert({
                            title: 'SAVED!',
                            message: 'Successfully saved.',
                            type: 'is-success',
                            confirmText: 'OK',
                            onConfirm: () => {
                                window.location = '/cpanel/users';
                            }
                        })
                    }
                }).catch(err=>{
                    if(err.response.status === 422){
                        this.errors = err.response.data.errors;
                    }
                });


            }
        },

        //update code here
        getData: function(data_id){
            this.clearFields();
            this.global_id = data_id;
            this.isModalCreate = true;


            //nested axios for getting the address 1 by 1 or request by request
            axios.get('/users/'+data_id).then(res=>{
                this.fields = res.data;
                this.fields.office = res.data.office_id;
                let tempData = res.data;
                //load city first
                axios.get('/load-cities?prov=' + this.fields.province).then(res=>{
                    //load barangay
                    this.cities = res.data;
                    axios.get('/load-barangays?prov=' + this.fields.province + '&city_code='+this.fields.city).then(res=>{
                        this.barangays = res.data;
                        this.fields = tempData;
                    });
                });
            });
        },

        clearFields(){
            this.fields = {
                username: '',
                lname: '', fname: '', mname: '',
                password: '', password_confirmation : '',
                sex : '', role: '',  email : '', contact_no : '',
                province: '', city: '', barangay: '', street: ''
            };
        },

        initData: function(){
           let id = parseInt(this.propId);
            this.programs = JSON.parse(this.propPrograms);
        }


    },

    mounted() {
        this.loadProvince();
        this.initData();
    }

}
</script>
