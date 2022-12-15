<?php

namespace App\Http\Controllers\API;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => []]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $comments = Comment::all();
        return response()->json($comments);
    }


    public function store(Request $request)
    {
        $comment = new Comment();
        $comment->userId = Auth::user()->_id;
        $comment->musicId = $request->musicId;
        $comment->comment = $request->comment;

        if ($comment->save()) {
            return response()->json($comment);
        } else {
            return $this->jsonResponse(400, 'Cannot create comment', $comment);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $comment = Comment::find($id);
        if ($comment) {
            return response()->json($comment);
        } else {
            return $this->jsonResponse(400, 'Could not find comment', new Comment());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $comment = Comment::find($id);
        if ($comment) {
            $comment->userId = Auth::user()->_id;
            $comment->musicId = $request->musicId;
            $comment->comment = $request->comment;
            if ($comment->update()) {
                return response()->json($comment);
            } else {
                $this->jsonResponse(400, 'Cannot modify comment', new Comment());
            }
        } else {
            return $this->jsonResponse(400, 'Could not find comment', new Comment());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $comment = Comment::find($id);
        if ($comment) {
            if ($comment->delete()) {
                return $this->jsonResponse(200, 'Success', new Comment());
            } else {
                return $this->jsonResponse(400, 'Cannot delete this comment, please try again', new Comment());
            }
        } else {
            return $this->jsonResponse(400, 'Could not find this comment', new Comment());
        }
    }

    public function jsonResponse(int $code, string $message, object $data)
    {
        return response()->json([
            'code' => $code,
            'message' => $message,
            'data' => $data,
        ], $code);
    }
}