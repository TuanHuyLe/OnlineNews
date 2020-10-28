<?php

namespace App\Http\Controllers;

use App\News;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    private $news;

    public function __construct(News $news)
    {
        $this->news = $news;
    }

    public function index(){
        $newsItem = $this->news->latest()->paginate(5);
        return view('home.home', compact('newsItem'));
    }
}
