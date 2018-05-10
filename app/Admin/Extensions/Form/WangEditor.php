<?php

/**
 * @Author: admin
 * @Date:   2017-11-07 16:49:43
 * @Last Modified by:   admin
 * @Last Modified time: 2017-11-30 17:33:36
 */

namespace App\Admin\Extensions\Form;

use Encore\Admin\Form\Field;

class WangEditor extends Field
{
    protected $view = 'laravel-admin.extensions.form.wang-editor';

    protected static $css = [
        '/packages/laravel-admin/wangEditor-3.0.12/release/wangEditor.min.css',
    ];

    protected static $js = [
        '/packages/laravel-admin/wangEditor-3.0.12/release/wangEditor.min.js',
    ];

    public function render()
    {
        $name = $this->formatName($this->column);

        $this->script = <<<EOT

var E = window.wangEditor
var editor = new E('#{$this->id}');
editor.customConfig.uploadImgShowBase64 = true
editor.customConfig.onchange = function (html) {
    $('input[name=$name]').val(html);
}
editor.create()

EOT;
        return parent::render();
    }
}