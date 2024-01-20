<?php

namespace App\Http\Controllers\API\Posts;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Traits\FileTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    use FileTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::paginate(10);
        return response()->json([$posts], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // return response()->json([$request->description]);
        //Validate the data from request
        $post = $request->validate([
            'title' => 'string',
            'description' => 'string',
            'cover_image' => 'nullable'
        ]);

        //Get the file string from request
        $image_string = $request->input('cover_image');
        //Decoding the image string and storing it to the storage
        $cover_image_url = $this->storeBase64File($image_string, 'posts/cover_images');

        //Modifying post attributes
        $post['user_id'] = Auth::user()->id;
        $post['cover_image'] = $cover_image_url;
        Post::create($post);

        return response()->json(['message' => 'Post Created Successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            //Validate the data from request
            $post = $request->validate([
                'title' => 'required|string',
                'description' => 'string',
                'cover_image' => 'nullable'
            ]);

            $oldPost = Post::findOrFail($id);

            //Checking if the request contain cover image
            if ($request->has('cover_image')) {
                //Get the file string from request
                $image_string = $request->input('cover_image');
                //Decoding the image string and storing it to the storage
                $cover_image_url = $this->storeBase64File($image_string, 'posts/cover_images');

                //Modifying the cover image url
                $post['cover_image'] = $cover_image_url;

                //Delete the old cover image
                $this->deleteFileFromStorage($oldPost->cover_image);
            }

            //Updating the post
            $postInstance = new Post();
            $postInstance->update($post);

            return response()->json(['message' => 'Post Updated Successfully']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Something went wrong in PostController.update',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
