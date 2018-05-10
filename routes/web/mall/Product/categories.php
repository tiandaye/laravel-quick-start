<?php

/**
 * 商品类目
 */
Route::get('mall/product/categories', ['as'=> 'mall.product.categories.index', 'uses' => 'Mall\Product\CategoryController@index']);
Route::post('mall/product/categories', ['as'=> 'mall.product.categories.store', 'uses' => 'Mall\Product\CategoryController@store']);
Route::get('mall/product/categories/create', ['as'=> 'mall.product.categories.create', 'uses' => 'Mall\Product\CategoryController@create']);
Route::put('mall/product/categories/{categories}', ['as'=> 'mall.product.categories.update', 'uses' => 'Mall\Product\CategoryController@update']);
Route::patch('mall/product/categories/{categories}', ['as'=> 'mall.product.categories.update', 'uses' => 'Mall\Product\CategoryController@update']);
Route::delete('mall/product/categories/{categories}', ['as'=> 'mall.product.categories.destroy', 'uses' => 'Mall\Product\CategoryController@destroy']);
Route::get('mall/product/categories/{categories}', ['as'=> 'mall.product.categories.show', 'uses' => 'Mall\Product\CategoryController@show']);
Route::get('mall/product/categories/{categories}/edit', ['as'=> 'mall.product.categories.edit', 'uses' => 'Mall\Product\CategoryController@edit']);
