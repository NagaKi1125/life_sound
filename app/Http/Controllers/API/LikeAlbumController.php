<?php

namespace App\Http\Controllers\API;

use App\Models\LikeAlbum;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LikeAlbumController extends Controller
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
        $likeAlbums = LikeAlbum::all();
        return response()->json($likeAlbums);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $likeAlbum = new LikeAlbum();
        $likeAlbum->userId = Auth::user()->_id;
        $likeAlbum->albumId = $request->albumId;
        if ($likeAlbum->save()) {
            return response()->json($likeAlbum);
        } else {
            return $this->jsonResponse(400, 'Could not store', new LikeAlbum());
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LikeAlbum  $likeAlbum
     * @return \Illuminate\Http\Response
     */
    public function showByUser()
    {
        $likeAlbums = LikeAlbum::where('userId', '=', Auth::user()->_id)->get();
        return response()->json($likeAlbums);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LikeAlbum  $likeAlbum
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $likeAlbum = LikeAlbum::find($id);
        if ($likeAlbum) {
            if ($likeAlbum->delete()) {
                return $this->jsonResponse(200, 'Success', new LikeAlbum());
            } else {
                return $this->jsonResponse(400, 'Cannot delete', new LikeAlbum());
            }
        } else {
            return $this->jsonResponse(400, 'Could not find', new LikeAlbum());
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
