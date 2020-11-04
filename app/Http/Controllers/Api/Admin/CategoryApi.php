<?php

namespace App\Http\Controllers\Api\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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
            return response(['errorCode' => 400, 'message' => 'Data invalid!', 'time' => now()], 400);
        else {
            $categories = $this->category->all('id', 'name')
                ->skip(($request['page'] - 1) * $request['limit'])
                ->take($request['limit']);
        }
        return response()->json($categories, 200);
    }

    /**
     * Store a newly created resource in storage.
     * CreatedBy: LTQUAN (04/11/2020)
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $category = ['name' => $request->name, 'code' => Str::slug($request->name)];
        $count = $this->category->where('code', $category['code'])->count();
        if ($count > 0) {
            $response = ['errorCode' => 400, 'message' => 'Thể loại đã tồn tại trong hệ thống', 'time' => now()];
        } else {
            try {
                $this->category->create([
                    'name' => $category['name'],
                    'code' => $category['code']
                ]);
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
     * CreatedBy: LTQUAN (04/11/2020)
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function show($id)
    {
        $categories = $this->category->select('id', 'name')->where('id', $id)->get();
        if (sizeof($categories) > 0)
            return response()->json($categories[0], 200);
        return response(['errorCode' => 404,'message'=>'Thể loại không tồn tại', 'time'=>time()], 404);
    }

    /**
     * Update the specified resource in storage.
     * CreatedBy: LTQUAN (04/11/2020)
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $category = ['id' => $request->id, 'name' => $request->name, 'code' => Str::slug($request->name)];
        $categories = $this->category->where('code', $category['code'])->get();
        if (sizeof($categories) > 0 && $categories[0]->id != $category['id']) {
            $response = ['errorCode' => 400, 'message' => 'Thể loại đã tồn tại trong hệ thống', 'time' => now()];
        } else {
            try {
                $this->category->find($category['id'])->update([
                    'name' => $category['name'],
                    'code' => $category['code']
                ]);
                $response = ['errorCode' => 200, 'message' => 'Cập nhật thể loại thành công', 'time' => now()];
            } catch (\Exception $exception) {
                Log::error('Message: ' . $exception->getMessage() . ', Line: ' . $exception->getLine());
                $response = ['errorCode' => 500, 'message' => 'Có lỗi xảy ra, vui lòng thử lại', 'time' => now()];
            }
        }
        return response()->json($response, $response['errorCode']);
    }

    /**
     * Remove the specified resource from storage.
     * CreatedBy: LTQUAN (04/11/2020)
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $category = $this->category->find($id);
            if (isset($category))
                $category->delete();
            $response = ['errorCode' => 200, 'message' => 'Xóa thể loại thành công', 'time' => now()];
        } catch (\Exception $exception) {
            Log::error('Message: ' . $exception->getMessage() . ', Line: ' . $exception->getLine());
            $response = ['errorCode' => 500, 'message' => 'Có lỗi xảy ra, vui lòng thử lại', 'time' => now()];
        }
        return response()->json($response, $response['errorCode']);
    }
}
