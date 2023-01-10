<?php

namespace App\Http\Controllers\API;

use App\Models\LikeMusic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Comment;
use App\Models\Count;
use App\Models\Image;
use App\Models\ListenHistory;
use App\Models\Lyric;
use App\Models\Music;
use App\Models\User;
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
        $likeMusics = LikeMusic::select('musicId')->where('userId', Auth::user()->id)->get();
        $likes = [];
        foreach ($likeMusics as $lk) {
            $music = Music::find($lk->musicId);

            if ($music) {

                $authorList = array();
                foreach (explode('_', $music->authors) as $au) {
                    if ($au) {
                        $author = Author::find($au)->first();
                        if ($author) {
                            array_push($authorList, $author);
                        }
                    }
                }
                $music->authors = $authorList;

                // get LikedCount
                $likeCount = LikeMusic::where('musicId', $music->id)->get()->count();
                $music->likeCount = $likeCount;

                // get ListenCount
                $listenCount = Count::where('musicId', $music->id)->get()->count();
                $music->listenCount = $listenCount;

                array_push($likes, $music);
            }

        }

        return $likes;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($musicId)
    {
        $likeMusic = LikeMusic::where('musicId', $musicId)->first();
        if ($likeMusic) {
        } else {
            $lm = new LikeMusic();
            $lm->userId = Auth::user()->_id;
            $lm->musicId = $musicId;
            if ($lm->save()) {
                return $lm;
            } else {
                return $this->jsonResponse(400, 'Could not store', new LikeMusic());
            }
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