<template>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h3>Dashboard</h3>
                    </div>
                    <div class="card-body">
                        <div class="col-md-2">
                            <p class="mb-0">You are logged in as <b>{{user.username}}</b></p>
                        </div>
                        <div class="col-md-8">
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-secondary col-sm-12" @click="addPost()">Add</button>
                        </div>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Content</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="!!posts.length" >
                                    <td colspan="4" style="text-align:center;">Data not found!</td>
                                </tr>
                                <tr v-for="post in posts.data" :key="post.id">
                                    <td>{{ post.title }}</td>
                                    <td>{{ post.content }}...</td>
                                    <td>{{ post.post_date }}</td>
                                    <td>
                                        <button class="btn btn-warning" @click="updatePost(post.id)">
                                            Edit
                                        </button>
                                        <button class="btn btn-danger" @click="deletePost(post.id)">
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
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
    <Modal
    v-show="isModalVisible"
    @close="closeModal"
    >
        <template v-slot:header>
            {{ modalMode.header }}
        </template>

        <template v-slot:body>
            <div class="col-md-12 clearfix">
                <form action="javascript:void(0)" @submit="postContent" class="row" method="post">
                    <div class="form-group col-md-12">
                        <input type="text" class="form-control" v-model="post.title" name="title" id="title" placeholder="Input Post Title Here" />
                    </div>
                    <div class="form-group col-md-12">
                        <textarea
                            name="content"
                            id="content"
                            v-model="post.content"
                            placeholder="Input Post Content Here"
                            rows="3"
                            max-rows="6"
                            class="form-control"
                        ></textarea>
                    </div>
                    <div class="form-group">
                        <input type="hidden" class="form-control" v-model="post.id" name="id" id="id" />
                        <input type="hidden" name="_token" :value="csrf">
                    </div>
                     <button type="submit" class="btn btn-block btn-primary">SUBMIT</button>
                </form>
            </div>
        </template>

        <template v-slot:footer>
        </template>
    </Modal>
</template>

<script>
import Modal from './Modal.vue';
export default {
    name:"dashboard",
    components: {
      Modal,
    },
    data(){
        return {
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            user:this.$store.state.auth.user,
            authorization:this.$store.state.auth.authorization,
            posts:{},
            isModalVisible: false,
            modalMode: {
                header: "",
            },
            post: {
                id: null,
                title: "",
                content: "",
            },
            postUrl: "",
            isNew: true,
            processing: false,
        }
    },
    async created(){
        this.$root.$refs.headerLayout.fetchUser();
        axios.get('/api/post').then(({data})=>{
            this.posts = data.data
        })
        .catch(function (error) {
            console.error(error);
        });
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
        showModal() {
            this.isModalVisible = true;
        },
        closeModal() {
            this.isModalVisible = false;
        },
        addPost() {
            this.modalMode.header = "Add New Post"
            this.isNew = true
            this.postUrl = "/api/post"
            this.showModal()
        },
        updatePost(post_id) {
            this.modalMode.header = "Edit Post"
            this.isNew = false
            this.postUrl = "/api/post/".concat(post_id)
            // this.postUrl = "/api/post"
            this.showModal()
        },
        async deletePost(post_id) {
            this.processing = true
            axios.defaults.headers.common.Authorization = `Bearer ${this.authorization.token}`
            this.postUrl = "/api/post/".concat(post_id) 
            await axios.delete(this.postUrl).then(({data})=>{
                this.validationErrors = {}
                this.fetchContent()
            }).catch((error)=>{
                console.error(error);
            }).finally(()=>{
                this.processing = false
            })
        },
        async postContent(){
            this.processing = true
            axios.defaults.headers.common.Authorization = `Bearer ${this.authorization.token}`
            if (this.isNew) {
                await axios.post(this.postUrl,this.post).then(({data})=>{
                    this.validationErrors = {}
                    this.post = {
                        id: "",
                        title: "",
                        content: "",
                    }
                    this.closeModal()
                    this.fetchContent()
                }).catch((error)=>{
                    console.error(error);
                }).finally(()=>{
                    this.processing = false
                })
            } else {
                await axios.put(this.postUrl,this.post).then(({data})=>{
                    this.validationErrors = {}
                    this.post = {
                        id: "",
                        title: "",
                        content: "",
                    }
                    this.closeModal()
                    this.fetchContent()
                }).catch((error)=>{
                    console.error(error);
                }).finally(()=>{
                    this.processing = false
                })
            }
            
        },
        async fetchContent(){
            let url = "/api/post"
            axios.get(url).then(({data})=>{
                this.posts = data.data
            })
            .catch(function (error) {
                console.error(error);
            });
        },
    }
}
</script>