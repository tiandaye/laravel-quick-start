<?php

namespace App\Admin\Extensions\Tools\Filter;

use Encore\Admin\Admin;
use Encore\Admin\Grid\Tools\AbstractTool;
use Illuminate\Support\Facades\Request;

class ListRecommendTypeFilter extends AbstractTool
{
    protected function script()
    {
        // $type = Request::get('type') ? Request::get('type') : 'all';
        $url = Request::fullUrlWithQuery(['type' => '_type_']);

        return <<<EOT

$('input:radio.listrecommend-type').change(function () {

    var url = "$url".replace('_type_', $(this).val());
	console.log($(this).val());
    $.pjax({container:'#pjax-container', url: url });

});

EOT;
    }

    public function render()
    {
        Admin::script($this->script());

        $options = [
            'all' => '全部类型',
            'l'   => '推荐列表',
            'p'   => '图片标题',
        ];

        return view('laravel-admin.tools.list-recommend.listrecommed-type', compact('options'));
    }
}
