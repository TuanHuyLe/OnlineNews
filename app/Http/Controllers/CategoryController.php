<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    private $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

//    public function getAll()
//    {
//        $categories = $this->category->get();
//        return response()->json([
//            'code' => 200,
//            'categories' => $categories
//        ], 200);
//    }
}
