<template>
    <div>


    </div> <!--root div-->
</template>

<script>
export default {
    props: ['propUser'],
    data(){
        return{
            locale: undefined,
            isModalActive: false,
        }

    },

    methods: {

    },

    mounted() {


    },


}
</script>

<style scoped>


    @media only screen and (max-width: 1024px) {

    }

    @media only screen and (max-width: 768px) {
        .main-text-container{
            top: 230px;
            width: 100%;
            margin: 0;
        }
    }

    @media only screen and (max-width: 480px) {
        .main-text-container{
            padding: 15px;
        }
        .main-title{
            font-size: 1.2em;
        }

        .main-subtitle{
            font-size: .8em;
        }

    }





</style>
