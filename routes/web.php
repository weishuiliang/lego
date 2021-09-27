<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

//课程
Route::get('/admin/lesson/list', [\App\Http\Controllers\Admin\LessonController::class, 'list']);
Route::get('/admin/lesson/detail', [\App\Http\Controllers\Admin\LessonController::class, 'detail']);
Route::post('/admin/lesson/create', [\App\Http\Controllers\Admin\LessonController::class, 'create']);
Route::post('/admin/lesson/del', [\App\Http\Controllers\Admin\LessonController::class, 'del']);
Route::post('/admin/lesson/edit', [\App\Http\Controllers\Admin\LessonController::class, 'edit']);
Route::post('/admin/lesson/show', [\App\Http\Controllers\Admin\LessonController::class, 'show']);

//教师
Route::get('/admin/teacher/list', [\App\Http\Controllers\Admin\TeacherController::class, 'list']);
Route::post('/admin/teacher/create', [\App\Http\Controllers\Admin\TeacherController::class, 'create']);
Route::get('/admin/teacher/detail', [\App\Http\Controllers\Admin\TeacherController::class, 'detail']);
Route::post('/admin/teacher/del', [\App\Http\Controllers\Admin\TeacherController::class, 'del']);
Route::post('/admin/teacher/edit', [\App\Http\Controllers\Admin\TeacherController::class, 'edit']);
Route::post('/admin/teacher/show', [\App\Http\Controllers\Admin\TeacherController::class, 'show']);

//用户
Route::get('/admin/user/list', [\App\Http\Controllers\Admin\UserController::class, 'list']);
Route::get('/admin/user/detail', [\App\Http\Controllers\Admin\UserController::class, 'detail']);
//评测
Route::post('/admin/assess/create', [\App\Http\Controllers\Admin\AssessController::class, 'create']);
//轮播图
Route::post('/admin/rotation/save', [\App\Http\Controllers\Admin\RotationController::class, 'save']);
Route::get('/admin/rotation/list', [\App\Http\Controllers\Admin\RotationController::class, 'list']);

//作品
Route::get('/admin/works/list', [\App\Http\Controllers\Admin\WorksController::class, 'list']);
Route::get('/admin/works/detail', [\App\Http\Controllers\Admin\WorksController::class, 'detail']);
Route::post('/admin/works/create', [\App\Http\Controllers\Admin\WorksController::class, 'create']);
Route::post('/admin/works/edit', [\App\Http\Controllers\Admin\WorksController::class, 'edit']);
Route::post('/admin/works/show', [\App\Http\Controllers\Admin\WorksController::class, 'show']);



//oss
Route::post('/oss/upload/image', [\App\Http\Controllers\OssController::class, 'uploadImage']);
Route::any('/weixin/pay/notify', [\App\Http\Controllers\PayNotifyController::class, 'notify']);




//小程序接口
//用户
Route::post('/micro/user/login', [\App\Http\Controllers\Micro\UserController::class, 'login']);
Route::post('/micro/user/save', [\App\Http\Controllers\Micro\UserController::class, 'save']);
Route::get('/micro/user/info', [\App\Http\Controllers\Micro\UserController::class, 'info']);
Route::post('/micro/user/edit', [\App\Http\Controllers\Micro\UserController::class, 'edit']);
Route::post('/micro/user/decryptData', [\App\Http\Controllers\Micro\UserController::class, 'decryptData']);

//用户中心
Route::get('/micro/userCenter/index', [\App\Http\Controllers\Micro\UserCenterController::class, 'index']);
Route::get('/micro/userCenter/myLessonList', [\App\Http\Controllers\Micro\UserCenterController::class, 'myLessonList']);
Route::any('/micro/userCenter/myLessonCancel', [\App\Http\Controllers\Micro\UserCenterController::class, 'myLessonCancel']);

//轮播图
Route::get('/micro/rotation/list', [\App\Http\Controllers\Micro\RotationController::class, 'list']);

//课程
Route::get('/micro/lesson/list', [\App\Http\Controllers\Micro\LessonController::class, 'list']);
Route::get('/micro/lesson/detail', [\App\Http\Controllers\Micro\LessonController::class, 'detail']);
Route::get('/micro/lesson/appointment/list', [\App\Http\Controllers\Micro\LessonController::class, 'appointmentLessonlist']);

//教师
Route::get('/micro/teacher/list', [\App\Http\Controllers\Micro\TeacherController::class, 'list']);
Route::get('/micro/teacher/detail', [\App\Http\Controllers\Micro\TeacherController::class, 'detail']);

//订单
Route::post('/micro/order/create', [\App\Http\Controllers\Micro\PayController::class, 'createOrder']);
Route::post('/micro/order/query', [\App\Http\Controllers\Micro\PayController::class, 'queryOrderPayStatus']);
Route::any('/micro/order/detail', [\App\Http\Controllers\Micro\OrderController::class, 'detail']);

//课程
Route::post('/micro/works/list', [\App\Http\Controllers\Micro\WorksController::class, 'list']);
Route::any('/micro/works/detail', [\App\Http\Controllers\Micro\WorksController::class, 'detail']);
