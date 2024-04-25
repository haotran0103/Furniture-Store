
<template>
    <body class="sb-nav-fixed">
        <div class="hero_area">
            <Header />
            
        </div>
        <router-view></router-view>
        <Footer />
    </body>
</template>

<script>
import Header from '../components/user/Header.vue';
import Footer from '../components/user/Footer.vue';


export default {
    components:{
        Header,
        Footer,
    },
    mounted() {
        
    }
}
</script>