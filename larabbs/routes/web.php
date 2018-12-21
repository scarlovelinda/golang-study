<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// 用户模块
Route::get('/', '\App\Http\Controllers\LoginController@welcome');
// 注册页面
Route::get('/register','\App\Http\Controllers\LoginController$index');
// 注册行为
Route::post('/register', '\App\Http\Controllers\RegisterController@register');
// 登陆页面
Route::get('/login', '\App\Http\Controllers\LoginController@index')->name("login");
// 登陆行为
Route::post('/login', '\App\Http\Controllers\LoginController@login');


Route::group(['middleware' => 'auth:web'], function(){
    // 登出行为
    Route::get('/logout', '\App\Http\Controllers\LoginController@logout');

    // 个人设置页面
    Route::get('/user/me/setting', '\App\Http\Controllers\UserController@setting');
    // 个人设置操作
    Route::post('/user/me/setting', '\App\Http\Controllers\UserController@settingStore');

    // 文章列表页
    Route::get('/posts', '\App\Http\Controllers\PostController@index');
    // 创建文章
    Route::get('/posts/create', '\App\Http\Controllers\PostController@create');
    Route::post('/posts', '\App\Http\Controllers\PostController@store');
    // 文章搜索页
    Route::get('/posts/search', '\App\Http\Controllers\PostController@search');
    // 文章详情页
    Route::get('/posts/{post}', '\App\Http\Controllers\PostController@show');
    // 编辑文章
    Route::get('/posts/{post}/edit', '\App\Http\Controllers\PostController@edit');
    Route::put('/posts/{post}', '\App\Http\Controllers\PostController@update');
    // 删除文章
    Route::get('/posts/{post}/delete', '\App\Http\Controllers\PostController@delete');
    // 图片上传
    Route::post('/posts/image/upload', '\App\Http\Controllers\PostController@imageUpload');
    // 提交评论
    Route::post('/posts/{post}/comment', '\App\Http\Controllers\PostController@comment');

    // 赞
    Route::get('/posts/{post}/zan', '\App\Http\Controllers\PostController@zan');
    // 取消赞
    Route::get('/posts/{post}/unzan', '\App\Http\Controllers\PostController@unzan');

    // 个人中心
    Route::get('/user/{user}', '\App\Http\Controllers\UserController@show');
    Route::post('/user/{user}/fan', '\App\Http\Controllers\UserController@fan');
    Route::post('/user/{user}/unfan', '\App\Http\Controllers\UserController@unfan');

    // 专题详情页
    Route::get('/topic/{topic}', '\App\Http\Controllers\TopicController@show');
    // 投稿
    Route::post('/topic/{topic}/submit', '\App\Http\Controllers\TopicController@submit');

    // 通知
    Route::get('/notices', '\App\Http\Controllers\NoticeController@index');
});

// 管理后台
Route::group(['prefix' => 'admin'], function(){

    // 登录展示页面
    Route::get('/login', '\App\Admin\Controllers\LoginController@index');
    // 登录行为
    Route::post('/login', '\App\Admin\Controllers\LoginController@login');
    // 登出行为
    Route::get('/logout', '\App\Admin\Controllers\LoginController@logout');

    Route::group(['middleware' => 'auth:admin'], function(){
        // 首页
        Route::get('/home', '\App\Admin\Controllers\HomeController@index');

        Route::group(['middleware' => 'can:system'], function(){
            // 管理人员模块
            Route::get("/users", '\App\Admin\Controllers\UserController@index');
            Route::get("/users/create", '\App\Admin\Controllers\UserController@create');
            Route::post("/users/store", '\App\Admin\Controllers\UserController@store');
            Route::get("/users/{user}/role", '\App\Admin\Controllers\UserController@role');
            Route::post("/users/{user}/role", '\App\Admin\Controllers\UserController@storeRole');
            // 角色
            Route::get("/roles", '\App\Admin\Controllers\RoleController@index');
            Route::get("/roles/create", '\App\Admin\Controllers\RoleController@create');
            Route::post("/roles/store", '\App\Admin\Controllers\RoleController@store');
            Route::get("/roles/{role}/permission", '\App\Admin\Controllers\RoleController@permission');
            Route::post("/roles/{role}/permission", '\App\Admin\Controllers\RoleController@storePermission');
            // 权限
            Route::get("/permissions", '\App\Admin\Controllers\PermissionController@index');
            Route::get("/permissions/create", '\App\Admin\Controllers\PermissionController@create');
            Route::post("/permissions/store", '\App\Admin\Controllers\PermissionController@store');
        });

        Route::group(['middleware' => 'can:post'], function() {
            // 审核模块
            Route::get('/posts', '\App\Admin\Controllers\PostController@index');
            Route::post('/posts/{post}/status', '\App\Admin\Controllers\PostController@status');
        });

        Route::group(['middleware' => 'can:topic'], function() {
            Route::resource('topics', '\App\Admin\Controllers\TopicController', ['only' => ['index', 'create', 'store', 'destroy']]);
        });

        Route::group(['middleware' => 'can:notice'], function() {
            Route::resource('notices', '\App\Admin\Controllers\NoticeController', ['only' => ['index', 'create', 'store']]);
        });
    });

});


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
