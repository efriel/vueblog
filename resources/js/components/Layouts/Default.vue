<template>
    <div>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavDropdown">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <router-link :to="{name:'home'}" class="nav-link">Blog</router-link>
                        </li>
                        
                    </ul>
                    <div class="d-flex">
                        <ul class="navbar-nav">
                            <li class="nav-item" v-if="!!!user.name">
                                <router-link :to="{name:'login'}" class="nav-link">Login</router-link>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
        <main class="mt-3">
            <router-view></router-view>
        </main>
    </div>
</template>

<script>
export default {
    name:"defaultLayout",
    data(){
        return {
            user:this.$store.state.auth.user,
            authorization:this.$store.state.auth.authorization
        }
    },
    methods:{
    },
}
</script>