<template>
    <div id="main-content" class="blog-page">
        <div class="container">
            <div class="row clearfix">
                <div class="col-lg-8 col-md-12 left-box">
                    <div class="card single_post box">
                        <div class="body">
                            <div class="col-md-12">
                                <h3>{{ post.title }}</h3>
                                <p>{{ post.content }}</p>
                            </div>
                            <div class="col-md-2 offset-md-10">
                                <form action="javascript:void(0)" @submit="postLike" class="row" method="post" 
                                v-if="!liked">
                                    <input type="hidden" name="_token" :value="csrf">
                                    <button type="submit" class="btn btn-block btn-primary">Like</button>
                                </form>
                                <form action="javascript:void(0)" @submit="postUnLike" class="row" method="post"
                                v-if="liked">
                                    <input type="hidden" name="_token" :value="csrf">
                                    <button type="submit" class="btn btn-block btn-success">Liked</button>
                                </form>
                            </div>
                        </div>                        
                    </div>
                    <div class="card box">
                        <div class="body">
                            <ul class="comment-reply list-unstyled">
                                <li class="clearfix" v-for="(comment, index) in comments" :key="index">
                                    <div class="row text-box">
                                        <div class="col-md-12 hl">
                                            <small>{{ comment.date }}</small>
                                            <h5 class="m-b-0">{{ comment.name }}</h5>
                                            <p>{{ comment.body }}</p>
                                        </div>
                                        <div class="col-md-2 offset-md-10">
                                            <form action="javascript:void(0)" @submit="commentLike(comment.id)" class="row" method="post" 
                                            v-if="!comment.is_liked==1">
                                                <input type="hidden" name="_token" :value="csrf">
                                                <button type="submit" class="btn btn-sm btn-primary">Like</button>
                                            </form>
                                            <form action="javascript:void(0)" @submit="commentUnLike(comment.id)" class="row" method="post"
                                            v-if="comment.is_liked==1">
                                                <input type="hidden" name="_token" :value="csrf">
                                                <button type="submit" class="btn btn-sm btn-success">Liked</button>
                                            </form>
                                        </div> 
                                    </div>
                                </li>
                            </ul>                                        
                        </div>
                    </div>
                    <div class="card box">
                        <div class="header">
                            <h3>Leave a reply</h3>
                        </div>
                        <div class="body">
                            <div class="comment-form">
                                <form action="javascript:void(0)" @submit="postComment" class="row" method="post">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <input type="text" class="form-control" v-model="newcomment.body" name="body" id="body" />
                                        </div>
                                    </div> 
                                    <div class="col-sm-12 pull-right">
                                        <button type="submit" class="btn btn-block btn-primary col-sm-12">SUBMIT</button>
                                    </div>                            
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12 right-box">
                    
                </div>
            </div>

        </div>
    </div>
</template>

<style>
    .box{
        padding: 20px;
        margin: 5px;     
    }
</style>

<script>
import { mapActions } from 'vuex'
let postId = 0
export default {
    name:'postdetail',
    data() {
        return {
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            post:{},
            comments:{},
            newcomment:{
                body:"",
                post_id:"",
            },
            postLike: {
                id: null,
                _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            postUrl: "",
            liked: false,
            user:this.$store.state.auth.user,
            authorization:this.$store.state.auth.authorization
        }
    },
    async created() {
        const slug = this.$route.params.slug
        let url = "/api/post/"
        await axios.get(url.concat(slug)).then(({data})=>{
            this.post = data.data
            this.newcomment.post_id = data.data.id
            this.postLike.id = data.data.id
            postId = data.data.id
        })
        .catch(function (error) {
           console.error(error);
        });

        this.fetchComment()
        this.isLike()
    },
    computed() {
    },
    methods: {
        ...mapActions({
            toDetail:"/"
        }),
        async postComment(){
            this.processing = true
            axios.defaults.headers.common.Authorization = `Bearer ${this.authorization.token}`
            await axios.post('/api/comment',this.newcomment).then(({data})=>{
                this.validationErrors = {}
                this.newcomment.body = ""
                this.fetchComment()
            }).catch((error)=>{
                console.error(error);
            }).finally(()=>{
                this.processing = false
            })
        },
        async postLike(){
            this.processing = true
            axios.defaults.headers.common.Authorization = `Bearer ${this.authorization.token}`
            this.postUrl = "/api/post/like"
            await axios.post(this.postUrl, this.postLike).then(({data})=>{
                this.validationErrors = {}
                this.liked = !this.liked
            }).catch((error)=>{
                console.error(error);
            }).finally(()=>{
                this.processing = false
            })
        },
        async postUnLike(){
            this.processing = true
            axios.defaults.headers.common.Authorization = `Bearer ${this.authorization.token}`
            this.postUrl = "/api/post/unlike"
            await axios.post(this.postUrl, this.postLike).then(({data})=>{
                this.validationErrors = {}
                this.liked = !this.liked
            }).catch((error)=>{
                console.error(error);
            }).finally(()=>{
                this.processing = false
            })
        },
        async isLike(){
            this.processing = true
            axios.defaults.headers.common.Authorization = `Bearer ${this.authorization.token}`
            this.postUrl = "/api/post/islike"
            await axios.post(this.postUrl, this.postLike).then(({data})=>{
                this.validationErrors = {}
                this.liked = data.data ? !!data.data.is_liked : false
            }).catch((error)=>{
                console.error(error);
            }).finally(()=>{
                this.processing = false
            })
        },
        async fetchComment(){
            this.processing = true
            this.postUrl = "/api/comment/islike"
            axios.defaults.headers.common.Authorization = `Bearer ${this.authorization.token}`
            await axios.post(this.postUrl, this.postLike).then(({data})=>{
                this.validationErrors = {}
                this.comments = data.data.data
            }).catch((error)=>{
                console.error(error);
            }).finally(()=>{
                this.processing = false
            })
        },
        async commentLike(commentId){
            this.processing = true
            axios.defaults.headers.common.Authorization = `Bearer ${this.authorization.token}`
            this.postUrl = "/api/comment/like"
            const commentData = {
                id: commentId,
            }
            await axios.post(this.postUrl, commentData).then(({data})=>{
                this.validationErrors = {}
                this.fetchComment()
            }).catch((error)=>{
                console.error(error);
            }).finally(()=>{
                this.processing = false
            })
        },
        async commentUnLike(commentId){
            this.processing = true
            axios.defaults.headers.common.Authorization = `Bearer ${this.authorization.token}`
            this.postUrl = "/api/comment/unlike"
            const commentData = {
                id: commentId,
            }
            await axios.post(this.postUrl, commentData).then(({data})=>{
                this.validationErrors = {}
                this.fetchComment()
            }).catch((error)=>{
                console.error(error);
            }).finally(()=>{
                this.processing = false
            })
        },
    }
} 
</script>