<template>
    <div class="container-fluid" id="post-create">
        <form @submit.prevent="savePost">
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title"
                       placeholder="title" v-model="title" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" rows="4" v-model="description" required></textarea>
            </div>
            <div class="mb-3">
                <label for="multipleImage" class="form-label">Multiple images</label>

                <input
                    ref="upload"
                    class="form-control"
                    type="file"
                    name="multi-file-upload"
                    accept="image/jpeg, image/jpg , image/png , image/gif"
                    id="multipleImage"
                    multiple=""
                    v-on:change="postMultipleImageChange"
                    required>

                <div class="row mt-3">
                    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3"
                         v-if="selected_post_multiple_images.length > 0"
                         v-for="(selected_post_multiple_image , index) in selected_post_multiple_images" :key="index">
                        <img :src="selected_post_multiple_image"
                             alt="Post Multiple Image" class="img-thumbnail img-vish">
                    </div>
                </div>
            </div>


            <div class="col-md-12" v-if="isProgressbar">
                <div class="progress m-3" style="height: 28px">
                    <div v-b-tooltip.hover
                         :aria-valuenow="percentage"
                         :style="'width:' + percentage + '%;padding: 16px;'"
                         :title="Math.round(percentage) + '%'"
                         aria-valuemax="100"
                         aria-valuemin="1"
                         class="progress-bar progress-bar-animated progress-bar-striped bg-info"
                         role="progressbar">
                        {{ percentage }} %
                    </div>
                </div>
            </div>

            <div class="text-center">
                <button class="btn btn-success btn-sm" :disabled="isLoading">
                    Store Post
                </button>
            </div>
        </form>
    </div>
</template>

<script>
export default {
    name: "PostCreate",
    data() {
        return {
            isLoading: false,
            isProgressbar: false,
            percentage: 1,

            title: '',
            description: '',

            post_multi_images: [],
            selected_post_multiple_images: [],
        }
    },
    methods: {
        savePost() {
            let self = this;
            self.isLoading = true;
            self.isProgressbar = true;
            console.clear();
            const config = {
                headers: {'content-type': 'multipart/form-data'},
                onUploadProgress: function (progressEvent) {
                    self.percentage = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                }
            }

            let formData = new FormData();
            formData.append('title', this.title);
            formData.append('description', this.description);

            $.each(this.post_multi_images, function (key, image) {
                formData.append(`post_multi_images[${key}]`, image);
            })

            axios.post('/post/store', formData, config)
                .then((response) => {
                    if (response.data.success === true) {
                        console.log(response.data.message);
                        self.$router.push('/');
                    } else {
                        self.isLoading = false;
                        console.log(response.data.message);
                    }
                })
                .catch((error) => {
                    self.isLoading = false;
                    console.log(error);
                })
        },

        postMultipleImageChange(event) {
            console.log(event.target.files);
            this.selected_post_multiple_images = [];
            this.post_multi_images = [];

            if (event.target.files) {
                var filesAmount = event.target.files.length;
                for (var i = 0; i < filesAmount; i++) {
                    const file = event.target.files[i];
                    if (file) {
                        var fileType = file["type"];
                        var validImageTypes = ["image/jpeg", "image/jpg", "image/png", "image/gif", "image/svg+xml"];
                        if ($.inArray(fileType, validImageTypes) < 0) {
                            alert('Invalid image format');
                            document.getElementById('multipleImage').value = '';
                            console.log(this.post_multi_images);
                            return false;
                        } else {
                            if (file.size / 1024 / 1024 <= 2) {
                                this.selected_post_multiple_images.push(URL.createObjectURL(file));
                                this.post_multi_images.push(event.target.files[i]);
                                console.log(this.post_multi_images);
                            } else {
                                alert('The image may not be greater than 2048 kilobytes.')
                                console.log(this.post_multi_images);
                                return false;
                            }
                        }
                    } else {
                        alert('Please Select Image')
                        this.post_multi_images = '';
                        return false;
                    }
                }
            } else {
                alert('Please Select Image')
                document.getElementById('multipleImage').value = '';
                this.post_multi_images = [];
                return false;
            }
        },
    }
}
</script>

<style scoped>
.img-vish {
    width: 80%;
}
</style>
