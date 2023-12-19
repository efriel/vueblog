import { createWebHistory, createRouter } from 'vue-router'
import store from '@/store'

/* Layouts */
const DefaultLayout = () => import('@/components/Layouts/Default.vue')
const HeaderLayout = () => import('@/components/Layouts/Header.vue')
/* Layouts */

/* Authenticated Component */
const Dashboard = () => import('@/components/Dashboard.vue')
/* Authenticated Component */

/* Guest Component */
const Login = () => import('@/components/Login.vue')
const Register = () => import('@/components/Register.vue')
const Home = () => import('@/components/Home.vue')
const BlogDetail = () => import('@/components/BlogDetail.vue')
/* Guest Component */

const routes = [
    {
        path: "/",
        component: HeaderLayout,
        meta: {
            middleware: "guest"
        },
        children: [
            {
                name: "home",
                path: '/',
                component: Home,
                meta: {
                    title: `Home`
                }
            }
        ]
    },
    {
        path: "/login",
        component: DefaultLayout,
        meta: {
            middleware: "guest"
        },
        children: [
            {
                name: "login",
                path: '/login',
                component: Login,
                meta: {
                    title: `Login`
                }
            }
        ]
    },
    {
        path: "/register",
        component: DefaultLayout,
        meta: {
            middleware: "guest"
        },
        children: [
            {
                name: "register",
                path: '/register',
                component: Register,
                meta: {
                    title: `Register`
                }
            }
        ]
    },
    {
        path: "/dashboard",
        component: HeaderLayout,
        meta: {
            middleware: "auth"
        },
        children: [
            {
                name: "dashboard",
                path: '/dashboard',
                component: Dashboard,
                meta: {
                    title: `Dashboard`
                }
            }
        ]
    },
    {
        path: "/:slug",
        component: HeaderLayout,
        meta: {
            middleware: "auth"
        },
        children: [
            {
                name: "blogdetail",
                path: '/:slug',
                component: BlogDetail,
                meta: {
                    title: `Blog Detail`
                }
            }
        ]
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes, 
})

router.beforeEach((to, from, next) => {
    document.title = to.meta.title
    if (to.meta.middleware === "guest") {
        next()
    } else {
        if (store.state.auth.authenticated) {
            next()
        } else {
            next({ name: "login" })
        }
    }
})

export default router