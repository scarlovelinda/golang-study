<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    // 个人设置页面
    public function setting()
    {
        $user = \Auth::user();
        return view('user.setting', compact('user'));
    }

    // 个人设置行为
    public function settingStore(Request $request)
    {
        // 验证
        $this->validate(request(), [
           'name' => 'required|min:3',
        ]);

        // 逻辑
        $name = request('name');
        $user = \Auth::user();
        if ($name != $user->name) {
            if (User::where('name', $name)->count() > 0) {
                return back()->withErrors('用户名称已经被注册');
            }
            $user->name = $name;
        }

        if ($request->file('avatar')) {
            $path = $request->file('avatar')->storePublicly($user->id);
            $user->avatar = "/storage/" . $path;
        }

        $user->save();

        // 渲染
        return back();
    }

    // 个人中心页面
    public function show(User $user)
    {
        // 这个人信息，包含关注／粉丝／文章数
        $user = User::withCount(['stars', 'fans', 'posts'])->find($user->id);

        // 这个人的文章列表，取创建时间最新的前10条
        $posts = $user->posts()->orderBy('created_at', 'desc')->take(10)->get();

        // 这个人关注的用户，包含关注用户的 关注／粉丝／文章数
        $stars = $user->stars;
        $susers = User::whereIn('id', $stars->pluck('star_id'))->withCount(['stars', 'fans', 'posts'])->get();

        // 这个人的粉丝用户，包含粉丝用户的 关注／粉丝／文章数
        $fans = $user->fans;
        $fusers = User::whereIn('id', $fans->pluck('fan_id'))->withCount(['stars', 'fans', 'posts'])->get();

        return view('user/show', compact('user', 'posts', 'susers', 'fusers'));
    }

    // 关注用户
    public function fan(User $user)
    {
        $me = \Auth::user();
        $me->doFan($user->id);

        return [
            'error' => 0,
            'msg' => ''
        ];
    }

    // 取消关注
    public function unfan(User $user)
    {
        $me = \Auth::user();
        $me->doUnfan($user->id);

        return [
            'error' => 0,
            'msg' => ''
        ];
    }
}