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
                        <li class="nav-item" v-if="!!user.name">
                            <router-link :to="{name:'dashboard'}" class="nav-link">Dashboard</router-link>
                        </li>
                    </ul>
                    <div class="d-flex">
                        <ul class="navbar-nav">
                            <li class="nav-item dropdown" v-if="!!user.name">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{ user.name }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuLink">
                                    <a class="dropdown-item" href="javascript:void(0)" @click="logout">Logout</a>
                                </div>
                            </li>
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
import {mapActions} from 'vuex'

export default {
    name:"headerLayout",
    data(){
        return {
            user:this.$store.state.auth.user,
            authorization:this.$store.state.auth.authorization
        }
    },
    created() {
        this.$root.$refs.headerLayout = this
    },
    methods:{
        ...mapActions({
            signOut:"auth/logout"
        }),
        async logout(){
            axios.defaults.headers.common.Authorization = `Bearer ${this.authorization.token}`
            await axios.post('/api/user/logout').then(({data})=>{
                localStorage.removeItem('user')
                localStorage.removeItem('vuex')
                this.user = {}
                this.signOut()
                this.$router.push({name:"home"})
            })
        },
        async fetchUser(){
            this.user = this.$store.state.auth.user
        },
    },
    mounted() {
    }
}
</script>