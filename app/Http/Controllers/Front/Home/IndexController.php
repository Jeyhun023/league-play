<?php

namespace App\Http\Controllers\Front\Home;

use App\Http\Controllers\Controller as BaseController;
use App\Http\Requests\Front\Home\IndexRequest;

class IndexController extends BaseController
{
    public function index(IndexRequest $request)
    {
        $data = $request->getViewSet();

        return view('front::jobs.index', ['data' => $data]);
    }
}
