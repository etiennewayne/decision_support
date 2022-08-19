<template>
    <div>

        <b-navbar class="is-light">
            <template #brand>
                <b-navbar-item>
                    CONTROL PANEL
                </b-navbar-item>
            </template>

            <template #start>

            </template>

            <template #end>
                <b-navbar-item href="/cpanel/dashboard">
                    Home
                </b-navbar-item>

                <b-navbar-dropdown label="Setting">

                    <b-navbar-item href="/cpanel/acad-years">
                        Academic Year
                    </b-navbar-item>
                    <b-navbar-item href="/cpanel/programs">
                        Program
                    </b-navbar-item>

                    <b-navbar-item href="/cpanel/courses">
                        Courses
                    </b-navbar-item>

                </b-navbar-dropdown>

                <b-navbar-dropdown label="Q&A">
                    <b-navbar-item href="/panel/question">
                        Question
                    </b-navbar-item>
                    <b-navbar-item href="/panel/student-answer">
                        Student Answer
                    </b-navbar-item>
                    <b-navbar-item href="/panel/student-result">
                        Student Result
                    </b-navbar-item>
                </b-navbar-dropdown>

                <b-navbar-dropdown label="Admission">
                    <b-navbar-item href="/panel/students-result">
                        Students Result
                    </b-navbar-item>

                    <!-- <b-navbar-item href="/panel/report-result">
                        General Report
                    </b-navbar-item> -->

                </b-navbar-dropdown>

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

        </b-navbar>



    </div>


</template>

<script>
export default {
    data(){
        return{

        }
    },
    methods: {
        logout(){
            axios.post('/logout').then(()=>{
                window.location = '/';
            })
        }
    }
}
</script>

<style scoped>

</style>
