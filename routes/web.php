<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great! 
post put delete any match
|
*/

Route::get('welcome', function () {
    return view('welcome');
});
Route::get('/',function(){
	return view("index");
});
Route::get('about',function(){
	return view("about");
});
Route::get('product',function(){
	return view("product");
});
// 服务页
Route::get('services', function () { 
    return view('services'); 
});
Route::get("user/{id?}",function($id=1){
	return "用户ID:".$id;
});
Route::get("page/{id}",function($id){
	return "页面ID：".$id;
})->where("id","[0-9]+");

//创建一个中间件路由分组
Route::middleware('auth')->group(function(){
	Route::get('dashboard',function(){
		return view('dashboard');
	});
	Route::get('account',function(){
		return view('account');
	});
});

Route::prefix('api')->group(function () {
    Route::get('/', function () {
    	return "api";
        // 处理 /api 路由
    })->name('api.index');
    Route::get('users', function () {
        // 处理 /api/users 路由
        return "api.user";
    })->name('api.users');
});

Route::get("task","TaskController@home");
Route::resource('post', 'PostController');
//路由绑定模型
Route::get('tash/{id}',function($id){
	$task = \App\Models\Task::findOrFail($id);
});
//兜底路由
Route::fallback(function () {
	//当用户访问的页面不存在时，使用路由给与回应。
    return '我是最后的屏障';
});
//频率限制
//使用场景：一个是在某些需要验证/认证的页面限制用户失败尝试次数，提高系统的安全性，另一个是避免非正常用户（比如爬虫）对路由的过度频繁访问，从而提高系统的可用性
//throttle:rate_limit,1用于自定义限制
Route::middleware('throttle:60,1')->group(function(){
	Route::get("/user222",function(){
		return "1111111111";
	});
});
Route::middleware('throttle:rate_limit,1')->group(function () {
    Route::get('/user', function () {
        // 在 User 模型中设置自定义的 rate_limit 属性值
    });
    Route::get('/post', function () {
        // 在 Post 模型中设置自定义的 rate_limit 属性值
    });
});

Route::get('tasker/{id}/delete', function ($id) {
    return '<form method="post" action="' . route('tasker.delete', [$id]) . '">
                <input type="hidden" name="_method" value="DELETE"> 
                <input type="hidden" name="_token" value="' . csrf_token() . '">
                <button type="submit">删除任务</button>
            </form>';
});

Route::delete('tasker/{id}', function ($id) {
    return 'Delete Task ' . $id;
})->name('tasker.delete');