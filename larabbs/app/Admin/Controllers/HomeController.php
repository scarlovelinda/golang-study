<?php

namespace APP\Admin\Controllers;

class HomeControllers extends Controllers
{
    //首页
    public function index()
    {
        return view('admin.home.index');
    }
    
}