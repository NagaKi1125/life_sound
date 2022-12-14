<?php

namespace App\Http\Controllers\API;

use App\Models\Count;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CountController extends Controller
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
        $counts = Count::all();
        return response()->json($counts);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $count = new Count();
        $count->userId = Auth::user()->_id;
        $count->musicId = $request->musicId;
        if ($count->save()) {
            return response()->json($count);
        } else {
            return $this->jsonResponse(400, 'Could not store', new Count());
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Count  $count
     * @return \Illuminate\Http\Response
     */
    public function showByUser()
    {
        $counts = Count::where('userId', '=', Auth::user()->_id)->get();
        return response()->json($counts);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Count  $count
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $count = Count::find($id);
        if ($count) {
            if ($count->delete()) {
                return $this->jsonResponse(200, 'Success', new Count());
            } else {
                return $this->jsonResponse(400, 'Cannot delete', new Count());
            }
        } else {
            return $this->jsonResponse(400, 'Could not find', new Count());
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
