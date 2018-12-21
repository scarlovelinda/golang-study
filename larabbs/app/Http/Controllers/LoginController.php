<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    // 登陆页面
    public function index()
    {
        return view('login.index');
    }
    //登陆行为
    public function login()
    {
        //验证
        $this->validate(request(),[
            'email' => 'required|email',
            'password' => 'required|min:5|max:10',
            'is_remember' => 'integer'
        ]);

        //逻辑
        $user = request(['email','password']);
        $is_remeber = boolval(request('is_remeber'));
        if (\Auth::attempt($user,$is_remeber)) {
            return redirect('/post');
        }


        //渲染
        return \Redirect::back()->withErrors("邮箱密码不匹配");
    }

    // 登出行为
    public function logout()
    {
        \Auth::logout();
        return redirect('/login');
    }

    public function welcome()
    {
        return redirect("/login");
    }
}
