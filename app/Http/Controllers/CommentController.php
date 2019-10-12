<?php

namespace App\Http\Controllers;

use App\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * @return View
     */
    public function index(){
        return view('blog-post',compact('comments'));
    }

    /**
     * @param $parent_id
     * Fetch all comments
     * @return false|string
     */
    public function comments($parent_id){
        $comments = Comment::where('parent_id',$parent_id)->orderBy('created_at','Desc')->get();
        $commentsData=[];
        foreach ($comments as $comment) {

            $replies = $comment->replies();
            $reply=0;
            if($replies->count()>0){
                $reply=1;
            }

            array_push($commentsData,[
                "name" => $comment->name,
                "commentid" => $comment->id,
                "comment" => $comment->comment_text,
                "reply" => $reply,
                'level'=>$comment->parents,
                "replies" => $replies,
                "date" => $comment->created_at->toDateTimeString()
            ]);
        }
        return json_encode($commentsData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $this->validate($request, [
            'name' => 'required',
            'comment' => 'required',
            'parrent_id' => 'filled',
        ]);
        $comment=new Comment();
        $comment->name=$request->name;
        $comment->comment_text=$request->comment;;
        $comment->parent_id=$request->parent_id;
        $comment->save();
        if($comment)
            return [ "status" => "true","commentId" => $comment->id ];
    }
}
