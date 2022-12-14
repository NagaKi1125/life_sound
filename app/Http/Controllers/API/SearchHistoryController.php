<?php

namespace App\Http\Controllers\API;

use App\Models\SearchHistory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SearchHistoryController extends Controller
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
        $serachHistories = SearchHistory::all();
        return response()->json($serachHistories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $searchHistory = new SearchHistory();
        $searchHistory->userId = Auth::user()->_id;
        $searchHistory->content = $request->content;
        if ($searchHistory->save()) {
            return response()->json($searchHistory);
        } else {
            $this->jsonResponse(400, 'Could not save content', new SearchHistory());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SearchHistory  $searchHistory
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $searchHistories = SearchHistory::where('userId', '=', Auth::user()->_id)->get();
        return response()->json($searchHistories);
    }


    public function destroy($id)
    {
        $searchHistory = SearchHistory::find($id);
        if ($searchHistory) {
            if ($searchHistory->delete()) {
                return $this->jsonResponse(200, 'Success', new SearchHistory());
            } else {
                return $this->jsonResponse(400, 'Could not delete', new SearchHistory());
            }
        } else {
            return $this->jsonResponse(400, 'Could not find', new SearchHistory());
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