<?php

namespace App\Http\Controllers;

use App\Category;
use App\News;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    private $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    public function index($categoryCode){
        $newsCategory = $this->category->where('code', $categoryCode)->first();
        $newsItem = News::query()->where('category_id', $newsCategory->id)->latest()->paginate(5);
        return view('home.home', compact('newsItem', 'newsCategory'));
    }
}
