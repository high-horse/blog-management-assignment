<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Traits\HandleErrorTrait;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    use HandleErrorTrait;
    public function __construct(){


    }

    public function getBlogs(Request $request) {
        try {
            $blogs = Blog::orderBy("created_at", "desc")
                ->with('user')
                ->Auther()
                ->select('id', 'title', 'description', 'user_id', 'created_at') // get user.id and user.name as well in this
                ->paginate($request->input('per_page', 15)); // Default to 15 if per_page is not provided

            $data = $blogs->map(function ($blog) {
                return [
                    'id' => $blog->id,
                    'title' => $blog->title,
                    'description' => $blog->description,
                    'user_id' => $blog->user->id,
                    'user_name' => $blog->user->name,
                    'created_at' => $blog->created_at->format('Y-m-d H:i:s'),
                ];
            });
    
            return response()->json([
                'status' => true,
                'blogs' => $data
            ]);
        } catch (\Throwable $th) {
            return $this->handleError($th);
        }
    }

    
    public function createBlog(Request $request) {
        $request->validate([
            'content' => 'required',
            'description' => 'required',
            'title' => 'required',
        ]);

        try {
            Blog::create([
                'content' => $request->input('content'),
                'description' => $request->input('description'),
                'title' => $request->input('title'),
                'user_id' => auth()->user()->id,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Blog created Successfully',
            ]);

        } catch (\Throwable $th) {
            return $this->handleError($th);
        }
    }

    public function getBlogDetail(Request $request, $id) {
        try {
            $blog = Blog::with('user')
                ->select('id', 'title', 'description', 'content', 'user_id', 'created_at')
                ->findOrFail($id);
    
            return response()->json([
                'status' => true,
                'blog' => [
                    'id' => $blog->id,
                    'title' => $blog->title,
                    'description' => $blog->description,
                    'content' => $blog->content,
                    'user_id' => $blog->user->id,
                    'user_name' => $blog->user->name,
                    'created_at' => $blog->created_at->format('Y-m-d H:i:s'),
                ]
            ]);
        } catch (\Throwable $th) {
            return $this->handleError($th);
        }
    }
    public function updateBlogDetail(Request $request, $id) {
        $request->validate([
            'content' => 'required',
            'description' => 'required',
            'title' => 'required',
        ]);
        try {
            $blog = Blog::findOrFail($id);
            $blog->update([
                'title' => $request->title,
                'description' => $request->description,
                'content'=> $request->content,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Blog Updated Successfully',
            ]);

        } catch (\Throwable $th) {
            return $this->handleError($th);
        }
    }
    public function deleteBlog(Request $request, $id) {
        try {
            $blog = Blog::findOrFail($id);
            $blog->delete();
            return response()->json([
                'status'=> true,
                'message' => 'blog deleted successfully',
            ]);
        } catch (\Throwable $th) {
            return $this->handleError($th);
        }
    }
}
