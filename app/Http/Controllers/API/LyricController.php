<?php

namespace App\Http\Controllers\API;

use App\Models\Lyric;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LyricController extends Controller
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
        $lyrics = Lyric::all();
        return response()->json($lyrics);
    }


    public function store(Request $request)
    {
        $lyric = new Lyric();
        $lyric->musicId = $request->musicId;
        $lyric->lyric = $request->lyric;

        if ($lyric->save()) {
            return response()->json($lyric);
        } else {
            return $this->jsonResponse(400, 'Cannot create lyric', $lyric);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Lyric  $lyric
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $lyric = Lyric::find($id);
        if ($lyric) {
            return response()->json($lyric);
        } else {
            return $this->jsonResponse(400, 'Could not find lyric', new Lyric());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Lyric  $lyric
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $lyric = Lyric::find($id);
        if ($lyric) {
            $lyric->lyric = $request->lyric;
            if ($lyric->update()) {
                return response()->json($lyric);
            } else {
                $this->jsonResponse(400, 'Cannot modify lyric', new Lyric());
            }
        } else {
            return $this->jsonResponse(400, 'Could not find lyric', new Lyric());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Lyric  $lyric
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $lyric = Lyric::find($id);
        if ($lyric) {
            if ($lyric->delete()) {
                return $this->jsonResponse(200, 'Success', new Lyric());
            } else {
                return $this->jsonResponse(400, 'Cannot delete this lyric, please try again', new Lyric());
            }
        } else {
            return $this->jsonResponse(400, 'Could not find this lyric', new Lyric());
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