<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('post_images')->get();
        if (count($posts) > 0) {
            return response()->json(['success' => true, 'posts' => $posts]);
        } else {
            return response()->json(['success' => false, 'message' => 'Post Not Found']);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required'],
            'description' => ['required'],
            'post_multi_images' => ['required'],
        ]);

        $post = new Post();
        $post->title = $request->title;
        $post->description = $request->description;
        $post->save();

        if ($post) {
            $path = "/public/post_images/";

            if (!Storage::exists($path)) {
                Storage::makeDirectory($path);
            }

            if ($request->hasFile('post_multi_images')) {
                $images_covers = $request->file('post_multi_images');
                $image_count = count($images_covers);
                for ($i = 0; $i < $image_count; $i++) {
                    $fileNameToStore = '';
                    if ($request->file('post_multi_images')[$i]) {
                        $images_cover = $request->file('post_multi_images')[$i];
                        $fileNamewithExt = $images_cover->getClientOriginalName();
                        $fileName = pathinfo($fileNamewithExt, PATHINFO_FILENAME);
                        $fileExtension = $images_cover->getClientOriginalExtension();
                        $fileNameToStore = 'p' . $post->id . '_' . md5('pi_' . date('Y_m_d_Hi', time())) . '_' . md5(microtime(true)) . '.' . $fileExtension;    //4. combinefile name and extension
                        $images_cover->storeAs('/public/post_images', $fileNameToStore);
                    } else {
                        $fileNameToStore = 'p_image.jpg';
                    }

                    $post_image = new PostImage();
                    $post_image->post_id = $post->id;
                    $post_image->image = 'post_images/' . $fileNameToStore;
                    $post_image->save();
                }
            }
            return response()->json(['success' => true, 'message' => 'post with multiple images store successfully']);
        }
    }
}
