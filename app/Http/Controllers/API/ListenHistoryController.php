<?php

namespace App\Http\Controllers\API;

use App\Models\ListenHistory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ListenHistoryController extends Controller
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
        $listenHistories = ListenHistory::all();
        return response()->json($listenHistories);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $listenHistory = new ListenHistory();
        $listenHistory->userId = Auth::user()->_id;
        $listenHistory->musicId = $request->musicId;
        if ($listenHistory->save()) {
            return response()->json($listenHistory);
        } else {
            return $this->jsonResponse(400, 'Could not store', new ListenHistory());
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ListenHistory  $listenHistory
     * @return \Illuminate\Http\Response
     */
    public function showByUser()
    {
        $listenHistories = ListenHistory::where('userId', '=', Auth::user()->_id)->get();
        return response()->json($listenHistories);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ListenHistory  $listenHistory
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $listenHistory = ListenHistory::find($id);
        if ($listenHistory) {
            if ($listenHistory->delete()) {
                return $this->jsonResponse(200, 'Success', new ListenHistory());
            } else {
                return $this->jsonResponse(400, 'Cannot delete', new ListenHistory());
            }
        } else {
            return $this->jsonResponse(400, 'Could not find', new ListenHistory());
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
