<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StorePostsRequest;
use App\Http\Requests\UpdatePostsRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Posts;
use App\Models\User;
use App\Models\Like;
use Response;

class PostsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'show', 'detail', 'search']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {   
        $data = DB::table('posts')
        ->select(
          'id as id',
          'title as title',
          'slug as slug',
          DB::raw("SUBSTRING(content, 1, 80) as content"),
          DB::raw("DATE_FORMAT(post_date, '%d-%b-%Y') as post_date"),
          'created_at as created_at')
        ->orderBy('created_at', 'DESC')
        ->paginate(6); 
        return response()->json([
            "success" => true,
            "data" => $data,
            "message" => 'List of Post'
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'=>'required',
            'content'=>'required',
        ]);

        $input = $request->all();
        $input['post_date']=date('Y-m-d H:i:s');
        $input['slug'] = $this->slugify($request->title);
        $input['author_id'] = Auth::user()->id;
        $post = Posts::create($input);

        return response()->json([
            "success" => true,
            "data" => $post,
            "message" => 'Success'
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        $data = DB::table('posts')
        ->select(
          'id as id',
          'title as title',
          'slug as slug',
          'content as content',
          DB::raw("DATE_FORMAT(post_date, '%d-%b-%Y') as post_date"),
          'created_at as created_at')
        ->where('slug','=',$slug)
        ->first();
        return response()->json([
            "success" => true,
            "data" => $data,
            "message" => 'Post Detail'
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title'=>'required',
            'content'=>'required',
        ]);
        $post = Posts::find($id);
        $post->title = $request->title;
        $post->content = $request->content;
        $post->save();

        return response()->json([
            "success" => true,
            "data" => $post,
            "message" => 'Success'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Posts $post)
    {
        $id = Posts::find($post->id);
        $post->delete();
        return response()->json([
            "success" => true,
            "data" => $post,
            "message" => 'Success'
        ], 200);
    }

    public function like(Request $request)
    {
        $post_id = $request->id;
        $user_id = Auth::user()->id;

        $like = Like::upsert([[
            'userable_type' => 'user', 
            'userable_id' => $user_id,
            'likeable_type' => 'post',
            'likeable_id' => $post_id,
            'is_liked' => 1,
            'created_at' => date('Y-m-d H:i:s'),
        ]], ['userable_type', 'userable_id', 'likeable_type', 'likeable_id', 'is_liked']);

        return response()->json([
            "success" => true,
            "data" => $like,
            "message" => 'Success'
        ], 200);
    }

    public function unlike(Request $request)
    {
        $post_id = $request->id;
        $user_id = Auth::user()->id;

        $like = Like::where('userable_id', $user_id)
        ->where('likeable_type', 'post')
        ->where('likeable_id', $post_id)
        ->delete();

        return response()->json([
            "success" => true,
            "data" => $like,
            "message" => 'Success'
        ], 200);
    }

    public function isLike(Request $request)
    {
        $post_id = $request->id;
        $user_id = Auth::user()->id;

        $like = Like::query()
        ->select('is_liked')
        ->where('likeable_id','=',$post_id)
        ->where('likeable_type', 'post')
        ->where('userable_id','=',$user_id)
        ->first();
        
        return response()->json([
            "success" => true,
            "data" => $like,
            "message" => 'Success'
        ], 200);
    }

    public function search(Request $request)
    {   
        $search = $request->search;
        $data = DB::table('posts')
        ->select(
          'id as id',
          'title as title',
          'slug as slug',
          DB::raw("SUBSTRING(content, 1, 80) as content"),
          DB::raw("DATE_FORMAT(post_date, '%d-%b-%Y') as post_date"),
          'created_at as created_at')
        ->where('title','LIKE','%'.$search.'%')
        ->orWhere('content','LIKE','%'.$search.'%')
        ->orderBy('created_at', 'DESC')
        ->paginate(6); 
        return response()->json([
            "success" => true,
            "data" => $data,
            "message" => 'Search Post'
        ], 200);
    }

    public static function slugify($text)
    {
      $text = preg_replace('~[^\pL\d]+~u', '-', $text);
      $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
      $text = preg_replace('~[^-\w]+~', '', $text);
      $text = trim($text, '-');
      $text = preg_replace('~-+~', '-', $text);
      $text = strtolower($text);
      if (empty($text)) {
        return 'n-a';
      }
      return str_slug($text);
    }
}
