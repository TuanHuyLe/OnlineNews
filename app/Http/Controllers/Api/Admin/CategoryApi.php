<?php

namespace App\Http\Controllers\Api\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
     * @return Application|ResponseFactory|JsonResponse|Response
     */
    public function index(Request $request)
    {
        $pageIndex = $request['page'];
        $limit = $request['pageSize'];
        $code = $request['filter'];
        $totalRecord = $this->category->where('code', 'like', '%' . $code . '%')->count();
        if (!isset($pageIndex) || !isset($limit))
            $categories = $this->category->all('id', 'name', 'description', 'created_at', 'updated_at');
        else if (!is_numeric($pageIndex) || !is_numeric($limit))
            return response(['errorCode' => 400, 'message' => 'Data invalid!', 'time' => now()], 400);
        else {
            $query = 'select id, name, code, created_at, updated_at, description from categories where deleted_at is null';
            if ($code != null) {
                $query .= ' and code like "%' . $code . '%"';
            }
            $query .= ' limit ' . $limit . ' offset ' . (($pageIndex - 1) * $limit);
//            $categories = $this->category->all('id', 'name', 'description', 'created_at', 'updated_at')
//                ->sortByDesc('created_at')
//                ->skip(($pageIndex - 1) * $limit)
//                ->take($limit)
//                ->values();
            $categories = DB::select($query);
        }
        return response()->json(['page' => $pageIndex, 'pageSize' => $limit, 'totalRecord' => $totalRecord, 'data' => $categories], 200);
    }

    /**
     * Store a newly created resource in storage.
     * CreatedBy: LTQUAN (04/11/2020)
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $category = ['name' => $request->name, 'code' => Str::slug($request->name), 'description' => $request->description];
        $count = $this->category->where('code', $category['code'])->count();
        if ($count > 0) {
            $response = ['errorCode' => 400, 'message' => 'Thể loại đã tồn tại trong hệ thống', 'time' => now()];
        } else {
            try {
                $this->category->create([
                    'name' => $category['name'],
                    'code' => $category['code'],
                    'description' => $category['description']
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
     * @return Application|ResponseFactory|JsonResponse|Response
     */
    public function show($id)
    {
        $categories = $this->category->select('id', 'name', 'description')->where('id', $id)->get();
        if (sizeof($categories) > 0)
            return response()->json($categories[0], 200);
        return response(['errorCode' => 404, 'message' => 'Thể loại không tồn tại', 'time' => time()], 404);
    }

    /**
     * Update the specified resource in storage.
     * CreatedBy: LTQUAN (04/11/2020)
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request)
    {
        $category = [
            'id' => $request->id,
            'name' => $request->name, 'code' => Str::slug($request->name),
            'description' => $request->description
        ];
        $categories = $this->category->where('code', $category['code'])->get();
        if (sizeof($categories) > 0 && $categories[0]->id != $category['id']) {
            $response = ['errorCode' => 400, 'message' => 'Thể loại đã tồn tại trong hệ thống', 'time' => now()];
        } else {
            try {
                $this->category->find($category['id'])->update([
                    'name' => $category['name'],
                    'code' => $category['code'],
                    'description' => $category['description'],
                    'updated_at' => now()
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
     * @param Request $request
     * @return JsonResponse
     */
    public function destroy(Request $request)
    {
        $ids = $request->request->all();
        try {
            if (isset($ids) && is_array($ids)) {
                foreach ($ids as $id) {
                    $this->category->find($id)->delete();
                }
                $response = ['errorCode' => 200, 'message' => 'Xóa thành công', 'time' => now()];
            } else {
                $response = ['errorCode' => 400, 'message' => 'Có lỗi xảy ra, vui lòng thử lại', 'time' => now()];
            }
        } catch (\Exception $exception) {
            Log::error('Message: ' . $exception->getMessage() . ', Line: ' . $exception->getLine());
            $response = ['errorCode' => 500, 'message' => 'Có lỗi xảy ra, vui lòng thử lại', 'time' => now()];
        }
        return response()->json($response, $response['errorCode']);
    }

    /**
     * Lấy danh sách thể loại
     * CreatedBy: LTQUAN (11/11/2020)
     */
    public function list()
    {
        $categories = Category::all('id', 'name');
        return response()->json($categories, 200);
    }
}
