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


}
