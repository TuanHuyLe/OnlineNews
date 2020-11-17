<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\News;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NewsApi extends Controller
{

    private $news;

    public function __construct(News $news)
    {
        $this->news = $news;
    }

    /**
     * Display a listing of the resource.
     * CreatedBy: LTQUAN (10/11/2020)
     * @param Request $request
     * @return Application|ResponseFactory|JsonResponse|Response
     */
    public function index(Request $request)
    {
        $pageIndex = $request['page'];
        $limit = $request['pageSize'];
        $filter = $request['filter'];
        $totalRecord = $this->news->where('id', 'like', '%'.$filter.'%')->count();
        if (!isset($pageIndex) || !isset($limit))
            $news = $this->news->all('id', 'name', 'description', 'created_at', 'updated_at');
        else if (!is_numeric($pageIndex) || !is_numeric($limit))
            return response(['errorCode' => 400, 'message' => 'Data invalid!', 'time' => now()], 400);
        else {
            $query = 'SELECT n.id, title, c.name as category, shortDescription, n.created_at, n.updated_at FROM news n left join categories c on n.category_id = c.id WHERE deleted_at IS NULL';
            if ($filter != null){
                $query .= ' and title like "%'.$filter.'%"';
            }
            $query .= ' limit '.$limit.' offset '.(($pageIndex - 1) * $limit);
            $news = DB::select($query);
        }
        return response()->json(['page'=>$pageIndex, 'pageSize'=>$limit, 'totalRecord'=>$totalRecord, 'data'=>$news], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $new = [
            'title' => $request->title,
            'shortDescription' => $request->shortDescription,
            'category_id'=>$request->category_id,
            'content'=>$request->contents
        ];
        if ($request->has('image_base64') && $request->has('image_name')){
            $imageName = $this->saveBase64($request->image_base64, $request->image_name, 'thumbnail');
            if ($imageName != null){
                $new['image'] = $imageName;
            }
        }
        $count = $this->news->where('title', $new['title'])->count();
        if ($count > 0) {
            $response = ['errorCode' => 400, 'message' => 'Bài viết đã tồn tại trong hệ thống', 'time' => now()];
        } else {
            try {
                $this->news->create($new);
                $response = ['errorCode' => 201, 'message' => 'Thêm mới thành công', 'time' => now()];
            } catch (\Exception $exception) {
                Log::error('Message: ' . $exception->getMessage() . ', Line: ' . $exception->getLine());
                $response = ['errorCode' => 500, 'message' => 'Có lỗi xảy ra, vui lòng thử lại', 'time' => now()];
            }
        }
        return response()->json($response, $response['errorCode']);
    }

    /**
     * Display the specified resource.
     *
     * CreatedBy: LTQUAN (10/11/2020)
     * @param int $id
     * @return Application|ResponseFactory|JsonResponse|Response
     */
    public function show($id)
    {
        $categories = $this->news->leftJoin('categories', 'news.category_id', '=', 'categories.id')
            ->select('news.id', 'title', 'category_id', 'content', 'name', 'shortDescription', 'image')
            ->where('news.id', $id)
            ->get();
        if (sizeof($categories) > 0)
            return response()->json($categories[0], 200);
        return response(['errorCode' => 404,'message'=>'Thể loại không tồn tại', 'time'=>time()], 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request)
    {
        $new = [
            'id' => $request->id,
            'title' => $request->title,
            'shortDescription' => $request->shortDescription,
            'category_id'=>$request->category_id,
            'content'=>$request->contents
        ];
        if ($request->has('image_base64') && $request->has('image_name')){
            $imageName = $this->saveBase64($request->image_base64, $request->image_name, 'thumbnail');
            if ($imageName != null){
                $new['image'] = $imageName;
            }
        }
        $news = $this->news->where('title', $new['title'])->get();
        if (sizeof($news) > 0 && $news[0]->id != $new['id']) {
            $response = ['errorCode' => 400, 'message' => 'Bài viết đã tồn tại trong hệ thống', 'time' => now()];
        } else {
            try {
                $new['updated_at'] = now();
                $this->news->find($new['id'])->update($new);
                $response = ['errorCode' => 200, 'message' => 'Cập nhật thành công', 'time' => now()];
            } catch (\Exception $exception) {
                Log::error('Message: ' . $exception->getMessage() . ', Line: ' . $exception->getLine());
                $response = ['errorCode' => 500, 'message' => 'Có lỗi xảy ra, vui lòng thử lại', 'time' => now()];
            }
        }
        return response()->json($response, $response['errorCode']);
    }

    /**
     * Remove the specified resource from storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function destroy(Request $request)
    {
        $ids = $request->request->all();
        try {
            if (isset($ids) && is_array($ids)){
                foreach ($ids as $id){
                    $this->news->find($id)->delete();
                }
                $response = ['errorCode' => 200, 'message' => 'Xóa thành công', 'time' => now()];
            }else{
                $response = ['errorCode' => 400, 'message' => 'Có lỗi xảy ra, vui lòng thử lại', 'time' => now()];
            }
        } catch (\Exception $exception) {
            Log::error('Message: ' . $exception->getMessage() . ', Line: ' . $exception->getLine());
            $response = ['errorCode' => 500, 'message' => 'Có lỗi xảy ra, vui lòng thử lại', 'time' => now()];
        }
        return response()->json($response, $response['errorCode']);
    }

    public function saveBase64($base64, $imageName, $folder){
        if($base64 == null || $imageName == null)
            return null;
        try {
            $base64 = str_replace(' ', '+', $base64);
            $imageName = $folder.'/'.Str::random(10).$imageName.'.png';
            Storage::disk('public')->put($imageName, base64_decode($base64));
            return '/storage/'.$imageName;
        }catch (\Exception $exception){
            return null;
        }
    }
}
