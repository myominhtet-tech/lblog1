<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Comment;
class CommentController extends Controller
{
    public function index() {
        $posts = Auth::user()->posts;
        return view('author.comments',compact('posts'));
    }

    public function destory($id) {
        Comment::findOrFail($id)->delete();
        return redirect()->back()->with('successMsg', 'Comment Successfully Remove From List');
    }
}
