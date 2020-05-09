<?php

namespace App\Http\Controllers;

use App\Post;
use App\Vote;
use Illuminate\Http\Request;
use App\Http\Requests\Post\Image\CreateImagePostRequest;
use App\Http\Requests\Post\Video\CreateVideoPostRequest;
//use App\Http\Requests\Post\Video\UploadVideoRequest;
use \Cviebrock\EloquentSluggable\Services\SlugService;
use Auth;
use Illuminate\Support\Str;
use Validator;
use Session;
use DB;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::all();

        return view('home')->with('posts', $posts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        //
    }

    public function imagePost(CreateImagePostRequest $request)
    {
        $user = Auth::user();

        $post = Post::create([

            'title' => $request->title,
            'slug' => $request->slug,
            'status' => 'published',
            'type' => 'image', 
            'description'  => $request->description, 
            'user_id' => $user->id,

        ]);

        if (request()->has('sellable')) {

            $uuid = Str::uuid()->toString();
            
            $post->update([
                'sellable' => 1,
                'royaltyFee' => $request->royaltyFee,
                'price' => $request->price,
                'download_id' => $uuid,
            ]);

            if (request()->hasFile('dContent')) {
                $post->addMediaFromRequest('dContent')->toMediaCollection('downloads');
            }   
        }

        if (request()->hasFile('mainImage')) {
            $post->addMediaFromRequest('mainImage')->toMediaCollection('images');
        }

        session()->flash('success', 'Image posted successfully');

        return redirect(route('home'));
    }

    public function uploadVideo(Request $request)
    {
        $rules = array(
            'mainVideo' => 'required|mimetypes:video/x-ms-asf,video/x-flv,video/mp4,application/x-mpegURL,video/MP2T,video/3gpp,video/quicktime,video/x-msvideo,video/x-ms-wmv,video/avi|max:256000',        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $user = Auth::user();

        $post = Post::create([

            'user_id' => $user->id,
            'type' => 'video',

        ]);

        if (request()->hasFile('mainVideo')) {
            $post->addMediaFromRequest('mainVideo')->toMediaCollection('video');
        }

        $output = array(
            'success' => 'Video uploaded successfully',
            'postId' => $post->id,
            'video' => '<div class="flex">
                            <video width="400" height="240" controls>
                                <source src="'.asset($post->getMedia('video')->first()->getUrl()).'" type="'.$post->mime_type.'">
                            </video>

                            <button type="button" id="del-video" class="align-middle mx-auto my-auto">Delete</button>

                            <script>
                                $(document).ready(function(){

                                    $("#del-video").click(function() {
                                        $("#success").empty();
                                        $("#uploadVid").toggleClass("hidden");
                                        $(".progress-bar").text("0%");
                                        $(".progress-bar").css("width", "0%");
                                        $("#formVidInfo").hide();
                                        $("#formVidInfo :input").attr("disabled", true);
                                        var _token = "'. Session::token() . '";
                                        $.ajax(
                                        {
                                            url: "'.route('deleteUploadedVideo', $post->id).'",
                                            type: "DELETE",
                                            dataType: "JSON",
                                            data: {
                                                "_method": "DELETE",
                                                "_token": _token,
                                            },
                                            success: function ()
                                            {
                                                console.log("Post Deleted SuccessFully");
                                            }
                                        });

                                    });
                                    
                                });
                            </script>
                        </div>',
        );

        return response()->json($output);
    }

    public function deleteUploadedVideo($id)
    {
        $post = Post::whereId($id)->firstOrFail();
        $post->clearMediaCollection();
        DB::table('media')->delete($id);
        $post->forceDelete();
        return response()->json();
    }

    public function videoPost(CreateVideoPostRequest $request)
    {

        $id = $request->id;

        $post = Post::whereId($id);

        $post->update([

            'title' => $request->title,
            'slug' => $request->slug,
            'status' => 'published',
            'description'  => $request->description,

        ]);

        if (request()->has('sellable')) {

            $uuid = Str::uuid()->toString();
            
            $post->update([
                'sellable' => 1,
                'royaltyFee' => $request->royaltyFee,
                'price' => $request->price,
                'download_id' => $uuid,
            ]);

            if (request()->hasFile('dContent')) {
                $post->addMediaFromRequest('dContent')->toMediaCollection('downloads');
            }   
        }

        session()->flash('success', 'Video posted successfully');

        return redirect(route('home'));
    }

    public function checkSlug(Request $request)
    {
        $slug = SlugService::createSlug(Post::class, 'slug', $request->title);

        return response()->json(['slug' => $slug]);
    }

    public function downloadable($download_id)
    {
        $post = Post::where('download_id', $download_id)->firstOrFail();

        return $post->getMedia('downloads')->first();
    }

    public function upVotePost(Request $request)
    {
        $user = Auth::user();
        $postId = $request->postId;
        $post = Post::whereId($postId)->first();
        $post->checkAndCreatePostUpVoteForUser($user->id);

        return response()->json(['votesCount' => $post->getTotalVoteCount()]);

    }

    public function downVotePost(Request $request)
    {
        $user = Auth::user();
        $postId = $request->postId;
        $post = Post::whereId($postId)->first();
        $post->checkAndCreatePostDownVoteForUser($user->id);

        return response()->json(['votesCount' => $post->getTotalVoteCount()]);
    }
}
