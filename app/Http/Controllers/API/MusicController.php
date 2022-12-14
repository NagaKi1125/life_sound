<?php

namespace App\Http\Controllers\API;

use App\Models\Music;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class MusicController extends Controller
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
        $musics = Music::all();
        return response()->json(
            $musics
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:10240',

        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $music = new Music();
        $time = date("Y-m-d-h-i-sa");
        $img_thumbnail = "";
        $image_type = 'THUMBNAIL';

        if ($request->hasFile('thumbnail')) {
            $image = new Image();
            $avaname = $time . 'life_sound--music' . $image_type . '--' . $request->file('thumbnail')->getClientOriginalName();
            $image->name = $avaname;
            $image->type = $image_type;

            if ($request->file('thumbnail')->move(public_path('images/uploads/'), $avaname)) {

                $img_thumbnail = "images/uploads/" . $avaname;
                $image->url = $img_thumbnail;
                if (!$image->save()) {
                    return $this->jsonResponse(400, 'Photo upload failed', new Image());
                }
            } else {
                $img_thumbnail = "not saved to public folder";
            }
        } else {
            $img_thumbnail = "1";
        }

        $music->url = $request->url;
        $music->category = $request->category;
        $music->name = $request->name;
        $music->thumbnail = $image->id;
        $music->author = $request->author;

        if ($music->save()) {
            return response()->json([
                $music
            ], 201);
        } else {
            return $this->jsonResponse(400, 'Cannot create music information', new Music());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Music  $music
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $music = Music::find($id);
        if ($music) {
            return response()->json($music);
        } else {
            return $this->jsonResponse(400, "Cannot find music", new Music());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Music  $music
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:10240',

        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $music = Music::find($id);
        $time = date("Y-m-d-h-i-sa");
        $img_thumbnail = "";
        $image_type = 'AVATAR';

        if ($request->hasFile('thumbnail')) {
            $image = new Image();
            $avaname = $time . 'life_sound--music' . $image_type . '--' . $request->file('thumbnail')->getClientOriginalName();
            $image->name = $avaname;
            $image->type = $image_type;

            if ($request->file('thumbnail')->move(public_path('images/uploads/'), $avaname)) {

                $img_thumbnail = "images/uploads/" . $avaname;
                $image->url = $img_thumbnail;
                if (!$image->save()) {
                    return $this->jsonResponse(400, 'Photo upload failed', new Image());
                }
            } else {
                $img_thumbnail = "not saved to public folder";
            }
        } else {
            $img_thumbnail = "1";
        }
        $music->url = $request->url;
        $music->category = $request->category;
        $music->name = $request->name;
        $music->thumbnail = $image->id;
        $music->author = $request->author;

        if ($music->save()) {
            return response()->json(
                $music
                ,
                201
            );
        } else {
            return $this->jsonResponse(400, 'Cannot update music information', new Music());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Music  $music
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $music = Music::find($id);
        if ($music) {

            if ($music->delete()) {
                return $this->jsonResponse(200, 'Success', new Music());
            } else {
                return $this->jsonResponse(400, 'Something went wrong', new Music());
            }

        } else {
            return $this->jsonResponse(
                400, 'Not found music with ' . $id,
                new Music()
            );
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
