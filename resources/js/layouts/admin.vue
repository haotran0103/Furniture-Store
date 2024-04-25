
<template>
    
    <body class="sb-nav-fixed">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <Header />
        <div id="layoutSidenav">
            <theSidebar />
            <div id="layoutSidenav_content">
            <router-view></router-view>
        </div>
    </div>
    </body>
</template>

<script>
import Header from '../components/admin/Header.vue';
import theSidebar from '../components/admin/theSidebar.vue';

export default {
    components:{
        Header,
        theSidebar
    },
    mounted() {
        const sidebarToggle = document.body.querySelector('#sidebarToggle');
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', event => {
                event.preventDefault();
                document.body.classList.toggle('sb-sidenav-toggled');
                localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
            });
        }
    }
}
</script>