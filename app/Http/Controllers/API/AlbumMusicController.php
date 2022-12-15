<?php

namespace App\Http\Controllers\API;

use App\Models\AlbumMusic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AlbumMusicController extends Controller
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
        $albumMusics = AlbumMusic::all();
        return response()->json($albumMusics);
    }


    public function store(Request $request)
    {
        $albumMusic = new AlbumMusic();
        $albumMusic->albumId = $request->albumId;
        $albumMusic->musicId = $request->musicId;

        if ($albumMusic->save()) {
            return response()->json($albumMusic);
        } else {
            return $this->jsonResponse(400, 'Cannot create albumMusic', $albumMusic);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AlbumMusic  $albumMusic
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $albumMusic = AlbumMusic::find($id);
        if ($albumMusic) {
            return response()->json($albumMusic);
        } else {
            return $this->jsonResponse(400, 'Could not find albumMusic', new AlbumMusic());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AlbumMusic  $albumMusic
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $albumMusic = AlbumMusic::find($id);
        if ($albumMusic) {
            $albumMusic->musicId = $request->musicId;
            if ($albumMusic->update()) {
                return response()->json($albumMusic);
            } else {
                $this->jsonResponse(400, 'Cannot modify albumMusic', new AlbumMusic());
            }
        } else {
            return $this->jsonResponse(400, 'Could not find albumMusic', new AlbumMusic());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AlbumMusic  $albumMusic
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $albumMusic = AlbumMusic::find($id);
        if ($albumMusic) {
            if ($albumMusic->delete()) {
                return $this->jsonResponse(200, 'Success', new AlbumMusic());
            } else {
                return $this->jsonResponse(400, 'Cannot delete this albumMusic, please try again', new AlbumMusic());
            }
        } else {
            return $this->jsonResponse(400, 'Could not find this albumMusic', new AlbumMusic());
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