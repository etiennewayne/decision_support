<template>
    <div>

        <b-navbar class="is-light">
            <template #brand>
                <b-navbar-item>
                    CONTROL PANEL ({{ userRole }})
                </b-navbar-item>
            </template>

            <template #start>

            </template>

            <template #end v-if="user.role ==='ADMINISTRATOR'">
                <b-navbar-item href="/cpanel/dashboard">
                    Home
                </b-navbar-item>
                <b-navbar-dropdown label="Setting">

                    <b-navbar-item href="/cpanel/acad-years">
                        Academic Year
                    </b-navbar-item>
                     <b-navbar-item href="/cpanel/rooms">
                        Rooms
                    </b-navbar-item>
                    <b-navbar-item href="/cpanel/programs">
                        Program
                    </b-navbar-item>

                    <b-navbar-item href="/cpanel/courses">
                        Courses
                    </b-navbar-item>

                    <b-navbar-item href="/cpanel/enrolment">
                        Enrolment
                    </b-navbar-item>

                </b-navbar-dropdown>

                <b-navbar-dropdown label="Faculty">

                    <b-navbar-item href="/cpanel/faculty">
                        Faculty
                    </b-navbar-item>

                    <b-navbar-item href="/cpanel/faculty-load">
                        Faculty Load
                    </b-navbar-item>


                </b-navbar-dropdown>

                <b-navbar-item href="/cpanel/schedules">
                    Schedules
                </b-navbar-item>

                <b-navbar-item href="/cpanel/users">
                    User
                </b-navbar-item>

                <b-navbar-item tag="div">
                    <div class="buttons">

                        <a class="button is-light" @click="logout">
                            <i class="fa fa-sign-out"></i>&nbsp;<strong>LOGOUT</strong>
                        </a>
                    </div>
                </b-navbar-item>
            </template>


            <template #end v-else-if="user.role ==='DEAN'">
                <b-navbar-item href="/cpanel/dashboard">
                    Home
                </b-navbar-item>

                <b-navbar-item href="/cpanel/course-list">
                    Course List
                </b-navbar-item>

                <b-navbar-dropdown label="Faculty">

                    <b-navbar-item href="/cpanel/faculty-load">
                        Faculty Load
                    </b-navbar-item>

                </b-navbar-dropdown>

                <b-navbar-item href="/cpanel/schedules">
                    Schedules
                </b-navbar-item>
                <b-navbar-item tag="div">
                    <div class="buttons">

                        <a class="button is-light" @click="logout">
                            <i class="fa fa-sign-out"></i>&nbsp;<strong>LOGOUT</strong>
                        </a>
                    </div>
                </b-navbar-item>
            </template>

            <template #end v-else-if="user.role ==='PROGRAM HEAD'">
                <b-navbar-item href="/cpanel/dashboard">
                    Home
                </b-navbar-item>

                <b-navbar-dropdown label="Setting">

                    <b-navbar-item href="/cpanel/programs">
                        Program
                    </b-navbar-item>

                </b-navbar-dropdown>

                <b-navbar-dropdown label="Faculty">

                    <b-navbar-item href="/cpanel/faculty-load">
                        Faculty Load
                    </b-navbar-item>

                </b-navbar-dropdown>



                <b-navbar-item tag="div">
                    <div class="buttons">

                        <a class="button is-light" @click="logout">
                            <i class="fa fa-sign-out"></i>&nbsp;<strong>LOGOUT</strong>
                        </a>
                    </div>
                </b-navbar-item>
            </template>

        </b-navbar>



    </div>


</template>

<script>
export default {
    data(){
        return{
            user: {
                role: '',
            },
        }
    },

    methods: {
        logout(){
            axios.post('/logout').then(()=>{
                window.location = '/';
            })
        },

        initUser(){
            axios.get('/get-user').then(res =>{
                this.user = res.data;
                ///console.log(this.user);
            })
        }
    },

    mounted(){
        this.initUser();
    },

    computed: {
        userRole(){
            if(this.user){
                return this.user.role.toUpperCase();
            }else{
                return '';
            }
        }
    }
}
</script>

<style scoped>

</style>
