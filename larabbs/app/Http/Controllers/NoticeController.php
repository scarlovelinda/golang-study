<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NoticeController extends Controller
{
    public function index()
    {
        // 获取当前用户
        $user = \Auth::user();

        $notices = $user->notices;

        return view('notice/index', compact('notices'));
    }
}