<?php

namespace App\Http\Controllers\API;

use App\Models\Author;
use App\Models\Image;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthorController extends Controller
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
        $authors = Author::all();
        return response()->json(
            $authors
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
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:10240',

        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $author = new Author();
        $time = date("Y-m-d-h-i-sa");
        $img_thumbnail = "";
        $image_type = 'AVATAR';

        if ($request->hasFile('avatar')) {
            $image = new Image();
            $avaname = $time . 'life_sound--author' . $image_type . '--' . $request->file('avatar')->getClientOriginalName();
            $image->name = $avaname;
            $image->type = $image_type;

            if ($request->file('avatar')->move(public_path('images/uploads/'), $avaname)) {

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

        $author->name = $request->name;
        $author->avatar = $image->id;
        $author->description = $request->description;

        if ($author->save()) {
            return response()->json([
                $author
            ], 201);
        } else {
            return $this->jsonResponse(400, 'Cannot create author information', new Author());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $author = Author::find($id);
        if ($author) {
            return response()->json($author);
        } else {
            return $this->jsonResponse(400, "Cannot find author", new Author);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:10240',

        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $author = Author::find($id);
        $time = date("Y-m-d-h-i-sa");
        $img_thumbnail = "";
        $image_type = 'AVATAR';

        if ($request->hasFile('avatar')) {
            $image = new Image();
            $avaname = $time . 'life_sound--author' . $image_type . '--' . $request->file('avatar')->getClientOriginalName();
            $image->name = $avaname;
            $image->type = $image_type;

            if ($request->file('avatar')->move(public_path('images/uploads/'), $avaname)) {

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

        $author->name = $request->name;
        $author->avatar = $image->id;
        $author->description = $request->description;

        if ($author->save()) {
            return response()->json(
                $author
                ,
                201
            );
        } else {
            return $this->jsonResponse(400, 'Cannot update author information', new Author());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $author = Author::find($id);
        if ($author) {

            if ($author->delete()) {
                return $this->jsonResponse(200, 'Success', new Author());
            } else {
                return $this->jsonResponse(400, 'Something went wrong', new Author());
            }

        } else {
            return $this->jsonResponse(
                400, 'Not found author with ' . $id,
                new Author()
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