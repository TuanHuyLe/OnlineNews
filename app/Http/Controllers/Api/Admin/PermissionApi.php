<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PermissionApi extends Controller
{

    private $permission;

    public function __construct(Permission $permission)
    {
        $this->permission = $permission;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
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
            $query = 'SELECT p.id, p.name, pc.name as parent, p.created_at, p.updated_at FROM permissions p left join permissions pc on p.parent_id = pc.id where p.deleted_at is null';
            if ($code != null){
                $query .= ' and name like "%'.$code.'%"';
            }
            $query .= ' limit '.$limit.' offset '.(($pageIndex - 1) * $limit);
            $roles = DB::select($query);
        }
        return response()->json(['page'=>$pageIndex, 'pageSize'=>$limit, 'totalRecord'=>$totalRecord, 'data'=>$roles], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function list(){
        $permissons = $this->permission->all('id', 'name', 'parent_id');
        return response()->json($permissons, 200);
    }
}
