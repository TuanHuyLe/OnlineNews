<?php

namespace App\Http\Controllers\Api\Web;

use App\Category;
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
        $total = $this->news->count('id');
        $categoryNews = null;
        if (!isset($pageIndex) || !isset($limit)) {
            $news = $this->news->query()->select('id', 'title', 'image', 'created_at', 'shortDescription')
                ->orderByDesc('created_at')->get();
        } else if (!is_numeric($pageIndex) || !is_numeric($limit)) {
            return response(['errorCode' => 400, 'message' => 'Data invalid!', 'time' => now()], 400);
        } else {
            $news = $this->news->query()->select('id', 'title', 'image', 'created_at', 'shortDescription');
            if (isset($request['category']) && $request['category'] !== 'all') {
                $categoryNews = Category::where('code', $request['category'])->first();
                if (isset($categoryNews)) {
                    $news = $news->where('category_id', $categoryNews->id);
                    $total = $news->count('id');
                }
            }
            $news = $news->skip(($request['page'] - 1) * $request['limit'])
                ->take($request['limit'])
                ->orderBy('created_at', 'desc')->get();
        }
        return response()->json([
            'status' => 200,
            'data' => $news,
            'total' => $total,
            'other' => $categoryNews
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
     * CreatedBy LHTUAN (06/11/2020)
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function show($id)
    {
        $news = News::query()->get(['id', 'title', 'content', 'created_at', 'image'])->find($id);
        if (!isset($news)) {
            return response(['errorCode' => 404, 'message' => 'User Id not found!', 'time' => now()], 404);
        }
        return response()->json([
            'status' => 200,
            'data' => $news
        ], 200);
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
