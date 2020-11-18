<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserApi extends Controller
{

    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Application|ResponseFactory|JsonResponse|Response
     */
    public function index(Request $request)
    {
        $pageIndex = $request['page'];
        $limit = $request['pageSize'];
        $code = $request['filter'];
        $totalRecord = $this->user->where('email', 'like', '%'.$code.'%')->count();
        if (!isset($pageIndex) || !isset($limit))
            $users = $this->user->all('id', 'name', 'email', 'created_at', 'updated_at');
        else if (!is_numeric($pageIndex) || !is_numeric($limit))
            return response(['errorCode' => 400, 'message' => 'Data invalid!', 'time' => now()], 400);
        else {
            $query = 'select id, name, email, created_at, updated_at from users where deleted_at is null';
            if ($code != null){
                $query .= ' and email like "%'.$code.'%"';
            }
            $query .= ' order by created_at desc limit '.$limit.' offset '.(($pageIndex - 1) * $limit);
            $users = DB::select($query);
        }
        return response()->json(['page'=>$pageIndex, 'pageSize'=>$limit, 'totalRecord'=>$totalRecord, 'data'=>$users], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $user = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'role_ids' => $request->role_ids
        ];
        $count = $this->user->where('email', $user['email'])->count();
        if ($count > 0) {
            $response = ['errorCode' => 400, 'message' => 'Thành viên đã tồn tại trong hệ thống', 'time' => now()];
        } else {
            try {
                DB::beginTransaction();
                $userEntity = $this->user->create([
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'password' => Hash::make($user['password'])
                ]);
                $userEntity->roles()->attach($user['role_ids']);
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
     *
     * @param  int  $id
     * @return Application|ResponseFactory|JsonResponse|Response
     */
    public function show($id)
    {
        $user = $this->user->select('id', 'name', 'email', 'password')->where('id', $id)->first();
        if ($user){
            $roles = DB::table('user_role')->where('user_id', '=', $id)->get('role_id');
            $role_ids = [];
            if(sizeof($roles) > 0){
                foreach ($roles as $role)
                    array_push($role_ids, $role->role_id);
            }
            $user['role_ids'] = $role_ids;
            return response()->json($user, 200);
        }
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
        $user = [
            'id' => $request->id,
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'role_ids' => $request->role_ids
        ];
        $users = $this->user->where('email', $user['email'])->get();
        if (sizeof($users) > 0 && $users[0]->id != $user['id']) {
            $response = ['errorCode' => 400, 'message' => 'THành viên đã tồn tại trong hệ thống', 'time' => now()];
        } else {
            try {
                DB::beginTransaction();
                $userEntity = $this->user->find($user['id']);
                if ($user['password'] != null){
                    $idUser = $userEntity->update([
                        'name' => $user['name'],
                        'email' => $user['email'],
                        'password' => Hash::make($user['password']),
                        'updated_at' => now()
                    ]);
                }else{
                    $idUser = $userEntity->update([
                        'name' => $user['name'],
                        'email' => $user['email'],
                        'updated_at' => now()
                    ]);
                }
                if ($idUser)
                    $userEntity->roles()->sync($user['role_ids']);
                DB::commit();
                $response = ['errorCode' => 200, 'message' => 'Cập nhật quyền hạn thành công', 'time' => now()];
            } catch (\Exception $exception) {
                Log::error('Message: ' . $exception->getMessage() . ', Line: ' . $exception->getLine());
                $response = ['errorCode' => 500, 'message' => 'Có lỗi xảy ra, vui lòng thử lại', 'time' => now()];
            }
        }
        return response()->json($response, $response['errorCode']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function destroy(Request $request)
    {
        $ids = $request->request->all();
        try {
            if (isset($ids) && is_array($ids)){
                foreach ($ids as  $id)
                    $this->user->find($id)->delete();
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
}
