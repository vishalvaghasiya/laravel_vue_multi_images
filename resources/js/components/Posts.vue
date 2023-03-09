<template>
    <div class="container-fluid" id="create-form">
        <div class="text-end mb-4">
            <router-link to="/create" exact class="btn btn-sm btn-success">
                Create New Post
            </router-link>
        </div>
        <div v-if="postsNotFoundMessage === ''">
            <table class="table table-responsive">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Title</th>
                    <th scope="col">Description</th>
                    <th scope="col" width="40%">Images</th>
                </tr>
                </thead>
                <tbody>
                <tr v-if="posts.length > 0" v-for="(post, index) in posts" :key="index">
                    <th scope="row">1</th>
                    <td>{{ post.title }}</td>
                    <td>{{ post.description }}</td>
                    <td>
                        <div v-if="post.post_images && post.post_images.length > 0" class="row">
                            <div v-for="(post_image , index) in post.post_images" :key="index"
                                 class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                                <img :src="'/storage/'+post_image.image" alt="post_image" class="img-fluid img-vish">
                            </div>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div v-else>
            <div class="card">
                <div class="card-body">
                    <h3 class="text-center text-danger">{{ postsNotFoundMessage }}</h3>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    name: "App",
    data() {
        return {
            posts: [],
            postsNotFoundMessage: ''
        }
    },
    methods: {
        getPosts() {
            let self = this;
            self.postsNotFoundMessage = '';
            axios.get('/post/fetch')
                .then((response) => {
                    if (response.data.success === true) {
                        console.log(response.data.posts);
                        self.posts = response.data.posts;
                    } else {
                        self.postsNotFoundMessage = response.data.message;
                        console.log(response.data.message);
                    }
                })
        }
    },
    created() {
        this.getPosts();
    }
}
</script>

<style scoped>
.img-vish {
    width: 60%;
}
</style>
