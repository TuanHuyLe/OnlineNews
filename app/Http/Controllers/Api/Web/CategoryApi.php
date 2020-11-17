<?php

namespace App\Http\Controllers\Api\Web;

use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryApi extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::query()->select(['id', 'name', 'code'])->get();
        if (!isset($categories) || $categories->isEmpty()) {
            return response(['status' => 204, 'message' => 'Danh sách thể loại trống!', 'time' => now()], 200);
        }
        return response()->json([
            'status' => 200,
            'data' => $categories
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
