<?php

namespace App\Http\Controllers\Api\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryApi extends Controller
{

    private $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    /**
     * Display a listing of the resource.
     * CreatedBy: LTQUAN (04/11/2020)
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pageIndex = $request['page'];
        $limit = $request['limit'];
        if (!isset($pageIndex) || !isset($limit))
            $categories = $this->category->all('id', 'name');
        else if (!is_numeric($pageIndex) || !is_numeric($limit))
            return response(['errorCode'=> 400, 'message'=>'Data invalid!', 'time'=>now()], 400);
        else{
            $categories = $this->category->all('id', 'name')
                ->skip(($request['page']-1)*$request['limit'])
                ->take($request['limit']);
        }
        return response()->json($categories, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * CreatedBy: LTQUAN (04/11/2020)
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return response($request->name);
    }

    /**
     * Display the specified resource.
     *
     * CreatedBy: LTQUAN (04/11/2020)
     * @param int $id
     * @return void
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * CreatedBy: LTQUAN (04/11/2020)
     * @param Request $request
     * @param int $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * CreatedBy: LTQUAN (04/11/2020)
     * @param int $id
     * @return void
     */
    public function destroy($id)
    {
        //
    }
}
