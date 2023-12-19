<template>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="row card-header">
                        <div class="col-sm-6">
                        <h3>Blogs</h3>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <input 
                                    v-on:keyup="searchPost(this.$data.name = $event.target.value)"
                                    type="text" 
                                    class="form-control" 
                                    v-model="search" 
                                    name="search"
                                    placeholder="Search Post" 
                                    id="search" />
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row postlist">
                            <div class="col-md-4" v-for="(post, index) in posts.data" :key="index">
                                <div class="card flex-md-row mb-4 box-shadow">
                                    <div class="card-body d-flex flex-column align-items-start">
                                        <h3 class="mb-0">
                                            <a class="text-dark" v-bind:href="post.slug">{{ post.title }}</a>
                                        </h3>
                                        {{ post.post_date }}
                                        <p class="card-text mb-auto _mb-desc">
                                        {{ post.content }}
                                        <a v-bind:href="post.slug">Read More</a></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row loadmore">
                            <div class="col-md-6 clearfix">
                                <button class="btn btn-primary col-sm-12"  v-if="!!posts.prev_page_url" @click="prevPage(posts.prev_page_url)">Previous Page </button>
                            </div>
                            <div class="col-md-6 clearfix">
                                <button class="btn btn-primary col-sm-12"  v-if="!!posts.next_page_url" @click="nextPage(posts.next_page_url)">Next Page </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name:'postlist',
    data() {
        return {
            posts:{},
            search: "",
            processing: false,
        }
    },
    created() {     
        axios.get('/api/post').then(({data})=>{
            this.posts = data.data
        })
        .catch(function (error) {
           console.error(error);
        });
    },
    computed() {
    },
    methods: {
        prevPage:function(prevPage){
            axios.get(prevPage).then(({data})=>{
                this.posts = data.data
            })
        },
        nextPage:function(nextPage){
            axios.get(nextPage).then(({data})=>{
                this.posts = data.data
            })
        },
        async searchPost(keyword){
            this.processing = true
            const postUrl = "/api/post/search"
            const searchKeyword = {
                search: keyword
            }
            await axios.post(postUrl, searchKeyword).then(({data})=>{
                this.validationErrors = {}
                this.posts = data.data
            }).catch((error)=>{
                console.error(error);
            }).finally(()=>{
                this.processing = false
            })
        },
    }
} 
</script>