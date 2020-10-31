<?php

namespace App\Http\Controllers;

use App\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{

    private $news;

    public function __construct(News $news)
    {
        $this->news = $news;
    }

    public function index($id){
        $news = $this->news->find($id);
        return view('home.news', compact('news'));
    }

    public function search(Request $request){
        $key = $request['key'];
        $newsItem = News::query()->where('title','like', '%'.$key.'%')
            ->paginate(5);
        return view('home.home', compact('newsItem', 'key'));
    }
}
