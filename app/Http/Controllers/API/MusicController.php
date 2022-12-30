<?php

namespace App\Http\Controllers\API;

use App\Models\ListenHistory;
use App\Models\Music;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Count;
use App\Models\Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;


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
        $musics = Music::all()->take(20);

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
            $listenHistory = new ListenHistory();
            $listenHistory->musicId = $music->id;
            $listenHistory->userId = Auth::user()->id;
            $listenHistory->save();

            $listenCount = new Count();
            $listenCount->userId = Auth::user()->id;
            $listenCount->musicId = $music->id;
            return $music;
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

    public function musicObject($name, $year)
    {
        $music = ["name" => $name, "year" => $year];
        return $music;
    }
    public function getRecommendation()
    {

        $musicList = [
            $this->musicObject('Come As You Are', 1991),
            $this->musicObject('Smells Like Teen Spirit', 1991),
            $this->musicObject('Heads Carolina, Tails California', 1996),
            $this->musicObject("Breakfast At Tiffany's", 1995),
            $this->musicObject('Lithium', 1992)
        ];

        $recommendation = Http::withBody(json_encode($musicList), 'application/json')->get('http://127.0.0.1:5000/recommend')->json();


        foreach ($recommendation as $result) {
            $artists = $result['artists'];
            $artist_list = '';
            // ---check and save author if not exits
            foreach ($artists as $artist) {
                $author = Author::where('spotify_id', $artist['spotify_id'])->first();
                if ($author) {
                    $artist_list = $artist_list . '_' . $author->id;
                } else {
                    $a = new Author();
                    $a->spotify_id = $artist['spotify_id'];
                    $a->name = $artist['name'];
                    $a->thumbnail = $artist['thumbnail'];
                    $a->popularity = $artist['popularity'];
                    if ($a->save()) {
                        $artist_list = $artist_list . '_' . $a->id;
                    }
                }
            }

            // check and save music if not exits
            $music = Music::where('spotify_id', $result['spotify_id'])->first();
            if ($music) {

            } else {
                $m = new Music();
                $m->authors = $artist_list;
                $m->preview_url = $result['preview_url'];
                $m->duration = $result['duration'];
                $m->name = $result['name'];
                $m->spotify_id = $result['spotify_id'];
                $m->year = $result['year'];
                $m->url = '';
                $m->category = '';
                $m->thumbnail = '';

                $m->save();
            }

        }

        return $recommendation;
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