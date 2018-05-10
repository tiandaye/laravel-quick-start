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

use Intervention\Image\Facades\Image;

Route::get('/', function () {
    return view('welcome');
	
	// 添加水印
	$img = Image::make(public_path('uploads/1.png'))->resize(1000, 1000)->insert(public_path('uploads/2.jpg'), 'bottom-right', 15, 10);
	return $img->response('png');

	// 缓存
	$img = Image::cache(function($image) {
	    $image->make(public_path('uploads/1.png'))->resize(300, 200)->greyscale();
	});
	return $img->response('png');

	// 压缩图片
	$img = Image::make(public_path('uploads/1.png'))->resize(500, 400);
	// 只改变宽度，高度不变，注意：会变形
	$img1 = $img->resize(300, null);
	// 宽度为300,高度自动调整，不会变形
	$img->resize(300, null, function ($constraint) {
	    $constraint->aspectRatio();
	})->save(public_path('uploads/foo1.png'));
	return $img1->response('png');

	// 旋转图片
	$img = Image::make(public_path('uploads/2.jpg'));
	$img = $img->rotate(-45);
	return $img->response('jpg');

	// 上传图片
	Image::make(public_path('uploads/1.png'))->resize(300, 200)->save(public_path('uploads/foo.png'));

	// 编辑图片
	$img = Image::make(public_path('uploads/1.png'))->resize(800, 500)->insert(public_path('uploads/2.jpg'));
	return $img->response('png');

	// 绘制图片
	$img = Image::canvas(800, 600, '#ccc');
	return $img->response('png');


	// 读取图片
	$img = Image::make(public_path('uploads/2.jpg'));
    return $img->response('jpg');
});
