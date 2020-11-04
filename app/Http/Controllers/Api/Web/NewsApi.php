<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Controller;
use App\News;
use Illuminate\Http\Request;

/**
 * Class NewsApi
 * @package App\Http\Controllers\Api\Web
 * Author: LHTUAN (04/11/2020)
 */
class NewsApi extends Controller
{
    private $news;

    public function __construct(News $news)
    {
        $this->news = $news;
    }

    /**
     * Display a listing of the resource.
     * CreatedBy LHTUAN (04/11/2020)
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pageIndex = $request['page'];
        $limit = $request['limit'];
        if (!isset($pageIndex) || !isset($limit)) {
            $news = $this->news->all('id', 'title', 'image', 'created_at', 'shortDescription');
        } else if (!is_numeric($pageIndex) || !is_numeric($limit)) {
            return response(['errorCode' => 400, 'message' => 'Data invalid!', 'time' => now()], 400);
        } else {
            $news = $this->news->all('id', 'title', 'image', 'created_at', 'shortDescription')
                ->skip(($request['page'] - 1) * $request['limit'])
                ->take($request['limit']);
        }
        return response()->json($news, 200);
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
