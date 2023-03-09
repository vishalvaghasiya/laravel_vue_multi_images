import PostCreate from "./components/PostCreate";
import Posts from "./components/Posts";

export default [
    {
        path: '/',
        name: 'posts',
        component: Posts
    },
    {
        path: '/create',
        name: 'post_create',
        component: PostCreate
    }
]
