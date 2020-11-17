<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Role;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RoleApi extends Controller
{

    private $role;

    public function __construct(Role $role)
    {
        $this->role = $role;
    }

    /**
     * Display a listing of the resource.
     * CreatedBy: LTQUAN (13/10/2020)
     * @param Request $request
     * @return Application|ResponseFactory|JsonResponse|Response
     */
    public function index(Request $request)
    {
        $pageIndex = $request['page'];
        $limit = $request['pageSize'];
        $code = $request['filter'];
        $totalRecord = $this->role->where('code', 'like', '%'.$code.'%')->count();
        if (!isset($pageIndex) || !isset($limit))
            $roles = $this->role->all('id', 'name', 'description', 'created_at', 'updated_at');
        else if (!is_numeric($pageIndex) || !is_numeric($limit))
            return response(['errorCode' => 400, 'message' => 'Data invalid!', 'time' => now()], 400);
        else {
            $query = 'select id, name, code, created_at, updated_at from roles where deleted_at is null';
            if ($code != null){
                $query .= ' and code like "%'.$code.'%"';
            }
            $query .= ' limit '.$limit.' offset '.(($pageIndex - 1) * $limit);
            $roles = DB::select($query);
        }
        return response()->json(['page'=>$pageIndex, 'pageSize'=>$limit, 'totalRecord'=>$totalRecord, 'data'=>$roles], 200);
    }

    /**
     * Store a newly created resource in storage.
     * CreatedBy: LTQUAN (13/10/2020)
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $role = [
            'name' => $request->name,
            'code' => $request->code,
            'permission_ids' => $request->permission_ids
        ];
        $count = $this->role->where('code', $role['code'])->count();
        if ($count > 0) {
            $response = ['errorCode' => 400, 'message' => 'Thể loại đã tồn tại trong hệ thống', 'time' => now()];
        } else {
            try {
                DB::beginTransaction();
                $roleEntity = $this->role->create([
                    'name' => $role['name'],
                    'code' => $role['code']
                ]);
                $roleEntity->permissions()->attach($role['permission_ids']);
                DB::commit();
                $response = ['errorCode' => 201, 'message' => 'Thêm mới thành công', 'time' => now()];
            } catch (\Exception $exception) {
                DB::rollBack();
                Log::error('Message: ' . $exception->getMessage() . ', Line: ' . $exception->getLine());
                $response = ['errorCode' => 500, 'message' => 'Có lỗi xảy ra, vui lòng thử lại', 'time' => now()];
            }
        }
        return response()->json($response, $response['errorCode']);
    }

    /**
     * Display the specified resource.
     * CreatedBy: LTQUAN (13/10/2020)
     * @param  int  $id
     * @return Application|ResponseFactory|JsonResponse|Response
     */
    public function show($id)
    {
        $role = $this->role->select('id', 'name', 'code')->where('id', $id)->first();
        if ($role){
            $permissions = DB::table('role_permission')
                ->where('role_id', '=', $id)
                ->get('permission_id');
            $permission_ids = [];
            if(sizeof($permissions) > 0){
                foreach ($permissions as $permission)
                    array_push($permission_ids, $permission->permission_id);
            }
            $role['permission_ids'] = $permission_ids;
            return response()->json($role, 200);
        }
        return response(['errorCode' => 404,'message'=>'Thể loại không tồn tại', 'time'=>time()], 404);
    }

    /**
     * Update the specified resource in storage.
     * CreatedBy: LTQUAN (13/10/2020)
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request)
    {
        $role = [
            'id' => $request->id,
            'name' => $request->name,
            'code' => $request->code,
            'permission_ids' => $request->permission_ids
        ];
        $roles = $this->role->where('code', $role['code'])->get();
        if (sizeof($roles) > 0 && $roles[0]->id != $role['id']) {
            $response = ['errorCode' => 400, 'message' => 'Vai trò đã tồn tại trong hệ thống', 'time' => now()];
        } else {
            try {
                $roleEntity = $this->role->find($role['id']);
                $id = $roleEntity->update([
                    'name' => $role['name'],
                    'code' => $role['code'],
                    'updated_at' => now()
                ]);
                if ($id)
                    $roleEntity->permissions()->sync($role['permission_ids']);
                $response = ['errorCode' => 200, 'message' => 'Cập nhật vai trò thành công', 'time' => now()];
            } catch (\Exception $exception) {
                Log::error('Message: ' . $exception->getMessage() . ', Line: ' . $exception->getLine());
                $response = ['errorCode' => 500, 'message' => 'Có lỗi xảy ra, vui lòng thử lại', 'time' => now()];
            }
        }
        return response()->json($response, $response['errorCode']);
    }

    /**
     * Remove the specified resource from storage.
     * CreatedBy: LTQUAN (13/10/2020)
     * @param Request $request
     * @return JsonResponse
     */
    public function destroy(Request $request)
    {
        $ids = $request->request->all();
        try {
            if (isset($ids) && is_array($ids)){
                foreach ($ids as  $id)
                    $this->role->find($id)->delete();
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

    /**
     * Lấy danh sách thể loại
     * CreatedBy: LTQUAN (11/11/2020)
     */
    public function list(){
        $roles = Role::all('id', 'name');
        return response()->json($roles, 200);
    }
}
