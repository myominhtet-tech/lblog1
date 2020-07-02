<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Comment;
use Symfony\Component\CssSelector\Node\FunctionNode;

class CommentController extends Controller
{
    public function index() {
        $comments = Comment::latest()->get();
        return view('admin.comments',compact('comments'));
    }

    public function destory($id) {
        Comment::findOrFail($id)->delete();
        return redirect()->back()->with('successMsg', 'Comment Successfully Remove From List');
    }
}
