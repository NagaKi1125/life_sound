<?php

namespace App\Http\Controllers\API;

use App\Models\Album;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AlbumMusic;
use App\Models\Author;
use App\Models\Count;
use App\Models\LikeMusic;
use App\Models\Lyric;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AlbumController extends Controller
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
        $albums = Album::where('userId', Auth::user()->id)->get();
        foreach($albums as $album){

            $musics = AlbumMusic::where('albumId',$album->id)->get();
            foreach ($musics as $music) {
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
    
                // get lyrics info
                $lyrics = Lyric::where('musicId', $music->id)->first();
                $music->lyric = $lyrics;
    
                // get LikedCount
                $likeCount = LikeMusic::where('musicId', $music->id)->get()->count();
                $music->likeCount = $likeCount;
    
                // get ListenCount
                $listenCount = Count::where('musicId', $music->id)->get()->count();
                $music->listenCount = $listenCount;
            }
            $album->musics = $music;

        }
        return response()->json($albums);
    }


    public function store(Request $request)
    {
        $album = new Album();
        $album->userId = Auth::user()->_id;
        $album->name = $request->name;

        if ($album->save()) {
            return response()->json($album);
        } else {
            return $this->jsonResponse(400, 'Cannot create album', $album);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $album = Album::find($id);
        if ($album) {
            return response()->json($album);
        } else {
            return $this->jsonResponse(400, 'Could not find album', new Album());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $album = Album::find($id);
        if ($album) {
            $album->userId = Auth::user()->_id;
            $album->name = $request->name;
            if ($album->update()) {
                return response()->json($album);
            } else {
                $this->jsonResponse(400, 'Cannot modify album', new Album());
            }
        } else {
            return $this->jsonResponse(400, 'Could not find album', new Album());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $album = Album::find($id);
        if ($album) {
            if ($album->delete()) {
                return $this->jsonResponse(200, 'Success', new Album());
            } else {
                return $this->jsonResponse(400, 'Cannot delete this album, please try again', new Album());
            }
        } else {
            return $this->jsonResponse(400, 'Could not find this album', new Album());
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
