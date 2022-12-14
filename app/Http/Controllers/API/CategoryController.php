<?php

namespace App\Http\Controllers\API;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Response;


class CategoryController extends Controller
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
        $category = Category::select('_id AS id','category')->get();
        return response()->json(
            $category
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
        $category = new Category();
        if (Category::where('category', $request->category)->first()) {
            return $this->jsonResponse(400, 'Already exits', $category);
        } else {
            $category->category = $request->category;
            if ($category->save()) {
                return response()->json($category);
            } else {
                return $this->jsonResponse(400, 'Cannot save category', new Category());
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::find($id);
        if ($category) {
            return response()->json($category);
        } else {
            return $this->jsonResponse(
                400, 'Not found category with ' . $id,
                new Category()
            );
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $category = Category::find($id);
        if ($category) {
            $category->category = $request->category;
            if ($category->update()) {
                return response()->json($category);
            } else {
                return $this->jsonResponse(400, 'Something went wrong', $category);
            }

        } else {
            return $this->jsonResponse(
                400, 'Not found category with ' . $id,
                new Category()
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::find($id);
        if ($category) {

            if ($category->delete()) {
                return $this->jsonResponse(200, 'Success', new Category());
            } else {
                return $this->jsonResponse(400, 'Something went wrong', new Category());
            }

        } else {
            return $this->jsonResponse(
                400, 'Not found category with ' . $id,
                new Category()
            );
        }
    }


    public function jsonResponse(int $code, string $message, Category $data)
    {
        return response()->json([
            'code' => $code,
            'message' => $message,
            'data' => $data,
        ], $code);
    }


}