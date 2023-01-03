<?php

namespace App\Http\Controllers\API;

use App\Models\LikedCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator; 

class LikedCategoryController extends Controller
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
        $likedCategories = LikedCategory::all();
        return response()->json($likedCategories);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $likedCategory = new LikedCategory();
        $likedCategory->userId = Auth::user()->_id;
        $likedCategory->categoryId = $request->categoryId;
        if ($likedCategory->save()) {
            return response()->json($likedCategory);
        } else {
            return $this->jsonResponse(400, 'Could not store', new LikedCategory());
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LikedCategory  $likedCategory
     * @return \Illuminate\Http\Response
     */
    public function showByUser()
    {
        $likeCategories = LikedCategory::where('userId', '=', Auth::user()->_id)->get();
        return response()->json($likeCategories);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LikedCategory  $likedCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $likedCategory = LikedCategory::find($id);
        if ($likedCategory) {
            if ($likedCategory->delete()) {
                return $this->jsonResponse(200, 'Success', new LikedCategory());
            } else {
                return $this->jsonResponse(400, 'Cannot delete', new LikedCategory());
            }
        } else {
            return $this->jsonResponse(400, 'Could not find', new LikedCategory());
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