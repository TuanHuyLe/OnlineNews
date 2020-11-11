<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Class AdminCategoryController
 * @package App\Http\Controllers\Admin
 * Author: LTQUAN (03/11/2020)
 */
class AdminCategoryController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * Author: LTQUAN (03/11/2020)
     */
    public function index(){
        return view('admin.category.index');
    }

}
