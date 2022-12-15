<?php

namespace App\Http\Controllers\API;

use App\Models\FollowAuthor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FollowAuthorController extends Controller
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
        $followAuthors = FollowAuthor::all();
        return response()->json($followAuthors);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $followAuthor = new FollowAuthor();
        $followAuthor->userId = Auth::user()->_id;
        $followAuthor->authorId = $request->authorId;
        if ($followAuthor->save()) {
            return response()->json($followAuthor);
        } else {
            return $this->jsonResponse(400, 'Could not store', new FollowAuthor());
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FollowAuthor  $followAuthor
     * @return \Illuminate\Http\Response
     */
    public function showByUser()
    {
        $followAuthors = FollowAuthor::where('userId', '=', Auth::user()->_id)->get();
        return response()->json($followAuthors);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FollowAuthor  $followAuthor
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $followAuthor = FollowAuthor::find($id);
        if ($followAuthor) {
            if ($followAuthor->delete()) {
                return $this->jsonResponse(200, 'Success', new FollowAuthor());
            } else {
                return $this->jsonResponse(400, 'Cannot delete', new FollowAuthor());
            }
        } else {
            return $this->jsonResponse(400, 'Could not find', new FollowAuthor());
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
