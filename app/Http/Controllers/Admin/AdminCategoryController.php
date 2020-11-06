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
    private $category;
    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * Author: LTQUAN (03/11/2020)
     */
    public function index(Request $request){
        return view('admin.category.index');
    }

}
