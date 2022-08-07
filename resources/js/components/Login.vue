<template>
    <div class="login-wrapper">
        <div class="login">

            <form @submit.prevent="submit">
                <div class="box">
                    <div class="title is-4">
                        SECURITY CHECK
                    </div>
                    <hr>

                    <div class="panel-body">
                        <div class="">
                            <img class="logo-img" src="/img/login-logo.png" />
                        </div>
                        <b-field label="Username" label-position="on-border"
                            :type="this.errors.username ? 'is-danger':''"
                            :message="this.errors.username ? this.errors.username[0] : ''">
                            <b-input type="text" v-model="fields.username" placeholder="Username" />
                        </b-field>

                        <b-field label="Password" label-position="on-border">
                            <b-input type="password" v-model="fields.password" password-reveal placeholder="Password" />
                        </b-field>

                        <div class="buttons">
                            <button class="button is-primary is-fullwidth">LOGIN</button>
                            <button class="button is-success is-outlined is-fullwidth">REGISTER HERE</button>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
</template>

<script>

export default {
    data(){
        return {
            fields: {
                username: null,
                password: null,
            },

            errors: {},
        }
    },

    methods: {
        submit: function(){
            axios.post('/login', this.fields).then(res=>{
                console.log(res.data)
                if(res.data.role === 'ADMINISTRATOR'){
                    window.location = '/cpanel/dashboard';
                }
                if(res.data.role === 'FACULTY'){
                    window.location = '/faculty/dashboard';
                }
               //window.location = '/dashboard';
            }).catch(err=>{
                if(err.response.status === 422){
                    this.errors = err.response.data.errors;
                }
            });
        }
    }
}
</script>


<style scoped>
    .login-wrapper{
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .login{
        width: 500px;
    }

    .box{
        border: 1px solid rgb(223, 223, 223);
    }

    .logo-img{
        width: 200px;
        margin: 15px auto;
        display: block;
    }


</style>
