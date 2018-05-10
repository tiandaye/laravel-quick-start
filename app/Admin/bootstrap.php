<?php

/**
 * Laravel-admin - admin builder based on Laravel.
 * @author z-song <https://github.com/z-song>
 *
 * Bootstraper for Admin.
 *
 * Here you can remove builtin form field:
 * Encore\Admin\Form::forget(['map', 'editor']);
 *
 * Or extend custom form field:
 * Encore\Admin\Form::extend('php', PHPEditor::class);
 *
 * Or require js and css assets:
 * Admin::css('/packages/prettydocs/css/styles.css');
 * Admin::js('/packages/prettydocs/js/main.js');
 *
 */

use App\Admin\Extensions\Column\ExpandRow;
use App\Admin\Extensions\Form\WangEditor;
use App\Admin\Extensions\Form\Ztree;
use Encore\Admin\Form;
use Encore\Admin\Grid\Column;

/**
 * 忽略 `map`, `editor` 控件
 */
Encore\Admin\Form::forget(['map', 'editor']);

/**
 * 修改命名空间
 */
// tian 修改 `laravel-admin` view, 便于修改, 这样就不需要动 `laravel-admin` 的源码。复制 `vendor/encore/laravel-admin/views` 到项目的 `resources/views/laravel-admin`
app('view')->prependNamespace('admin', resource_path('views/laravel-admin'));
// tian 修改 `laravel-admin` 的语言包, 复制 `vendor/encore/laravel-admin/lang` 到项目的 `resources/lang/admin`。如果将系统语言locale设置为 `zh-cn`, 可以将 `resources/lang/admin` 目录下的 `zh_CN` 目录重命名为 `zh-cn` 即可
// app('translator')->addNamespace('admin', resource_path('lang/admin'));


/**
 * 扩展 `form` 控件
 */
// 扩展 `ztree` 控件(树状控件)
Form::extend('ztree', Ztree::class);
// 扩展 `wangEditor` 控件(富文本控件)
Form::extend('wangEditor', WangEditor::class);

/**
 * 扩展列
 */
// 展开列
Column::extend('expand', ExpandRow::class);

/**
 * 引入 `layer` 插件
 */
// Admin::js('/packages/admin/layer/layer.js');

