<?php

namespace App\Http\Controllers\Web;

use App\News;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

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
