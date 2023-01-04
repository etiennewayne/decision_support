<template>
  <div>
    <section class="hero is-fullheight">
            <!-- Hero head: will stick at the top -->

        <div class="welcome-container">
            <div class="text-container">
                <div class="welcome-text">
                    CONTROL PANEL
                </div>
            </div>
            <div class="center-img">
                <img src="/img/dashboard-5050-2-550x550-1.webp" />
            </div>
        </div>

    </section>

  </div>
</template>

<script>
export default {
	data(){
		return{

		}
	},

	methods:{

	}
}
</script>

<style scoped>
    .center-img{
        display: flex;
        justify-content: center;
    }
    .welcome-container{
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }
    .text-container{
        display: flex;
        align-items: center;
    }
    .welcome-text{
        font-size: 2em;
        text-align: right;

    }
</style>
