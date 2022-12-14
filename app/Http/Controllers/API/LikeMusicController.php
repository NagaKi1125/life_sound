<?php

namespace App\Http\Controllers\API;

use App\Models\LikeMusic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LikeMusicController extends Controller
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
        $likeMusics = LikeMusic::all();
        return response()->json($likeMusics);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $likeMusic = new LikeMusic();
        $likeMusic->userId = Auth::user()->_id;
        $likeMusic->musicId = $request->musicId;
        if ($likeMusic->save()) {
            return response()->json($likeMusic);
        } else {
            return $this->jsonResponse(400, 'Could not store', new LikeMusic());
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LikeMusic  $likeMusic
     * @return \Illuminate\Http\Response
     */
    public function showByUser()
    {
        $likeMusics = LikeMusic::where('userId', '=', Auth::user()->_id)->get();
        return response()->json($likeMusics);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LikeMusic  $likeMusic
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $likeMusic = LikeMusic::find($id);
        if ($likeMusic) {
            if ($likeMusic->delete()) {
                return $this->jsonResponse(200, 'Success', new LikeMusic());
            } else {
                return $this->jsonResponse(400, 'Cannot delete', new LikeMusic());
            }
        } else {
            return $this->jsonResponse(400, 'Could not find', new LikeMusic());
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
