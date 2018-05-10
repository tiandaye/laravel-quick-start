<?php

/**
 * @Author: admin
 * @Date:   2017-11-30 16:51:36
 * @Last Modified by:   admin
 * @Last Modified time: 2017-11-30 17:33:26
 */

namespace App\Admin\Extensions\Form;

use Encore\Admin\Form\Field;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Str;

/**
 * laravel-admin中的使用例子:
 * $form->ztree('f_pid', '所属地区')->zTreeConfig(["map" => ["f_id" => "id", "alias" => 'name', "f_pid" => "pId"], "type" => "radio", "open" => true])->options($arrAreas)->load('city', '/admin/api/area/city');
 */
class Ztree extends Field
{
    protected $view = 'laravel-admin.extensions.form.ztree';

    protected static $css = [
        '/packages/laravel-admin/zTree/css/zTreeStyle/zTreeStyle.css',
    ];

    protected static $js = [
        '/packages/laravel-admin/zTree/js/jquery.ztree.core.min.js',
        '/packages/laravel-admin/zTree/js/jquery.ztree.excheck.min.js',
    ];

    // ztree的配置 type:单选还是多选, open:节点是否打开, map:字段映射
    protected $zTreeConfig = ["type" => "radio", "open" => true, "map" => ["f_id" => "id", "alias" => 'name', "f_pid" => "pId"]];
    // 是否需要联动
    protected $isTrigger = false;
    // 联动函数
    protected $callBack = "";

    public function zTreeConfig($config = [])
    {
        $this->zTreeConfig = array_merge($this->zTreeConfig, $config);

        return $this;
    }

    /**
     * Set options.
     *
     * @param array|callable|string $options
     *
     * @return $this
     */
    public function options($options = [])
    {
        if ($options instanceof Arrayable) {
            $options = $options->toArray();
        }

        $this->options = (array) $options;

        if (($this->zTreeConfig['map']) > 0) {
            $tempArr = [];
            foreach ($this->options as $key => $value) {
                $option = [];
                foreach ($this->zTreeConfig['map'] as $k => $v) {
                    if (is_array($value)) {
                        $option[$v] = $value[$k];
                    } else {
                        $option[$v] = $value->$k;
                    }
                }
                // 判断是否全部打开
                $option['open'] = $this->zTreeConfig['open'];
                $tempArr[] = $option;
            }
            $this->options = $tempArr;
        }

        return $this;
    }

    /**
     * Set options.
     *
     * @param array|callable|string $values
     *
     * @return $this
     */
    public function values($values)
    {
        return $this->options($values);
    }

    /**
     * Load options for other select on change.
     *
     * @param string $field
     * @param string $sourceUrl
     * @param string $idField
     * @param string $textField
     *
     * @return $this
     */
    public function load($field, $sourceUrl, $idField = 'id', $textField = 'text')
    {
        if (Str::contains($field, '.')) {
            $field = $this->formatName($field);
            $class = str_replace(['[', ']'], '_', $field);
        } else {
            $class = $field;
        }

        $this->isTrigger = true;

        $this->callBack = <<<EOT

        $("#{$this->id}-ztreeInput").val(treeNode["name"]);
        $("#{$this->id}-hidden-ztreeInput").val(treeNode["id"]);
        var target = $(".$class");
        console.log($(target));
        $.get("$sourceUrl?q="+treeNode["id"], function (data) {
            target.find("option").remove();
            $(target).select2({
                data: $.map(data, function (d) {
                    d.id = d.$idField;
                    d.text = d.$textField;
                    return d;
                })
            }).trigger('change');
        });
EOT;

        return $this;
    }

    /**
     * 渲染
     * @return [type]
     */
    public function render()
    {
        $options = json_encode($this->options, true);
        if (!$this->isTrigger) {
            $this->callBack = <<<EOT

            $("#{$this->id}-ztreeInput").val(treeNode["name"]);
            $("#{$this->id}-hidden-ztreeInput").val(treeNode["id"]);
EOT;
        }

        $this->script = <<<EOT

        var oZtree{$this->id} = $.fn.zTree.init($("#{$this->id}-ztreeMenu"), {
            check: {
                enable: true,
                chkStyle: "radio",
                radioType: "all",
            },
            data: {
                simpleData: {
                    enable: true
                }
            },
            callback: {
                onCheck: function (e, treeId, treeNode) {
                    {$this->callBack}
                }
            }
        }, {$options});

        backfillZtree{$this->id}();
EOT;

        return parent::render();
        //return parent::render()->with(['options' => $this->options])

    }
}

// //回填数据
// @if (old(\$column, \$value))
//  var val = "{{ old(\$column, \$value) }}";
//     if ($.isArray( val )) {
//         val = val;
//     }
//     else if ($.isPlainObject(val)) {
//         val = [val];
//     }
//     else if (val.indexOf(',')) {//joinsign 分割符
//         val = val.split(',');
//     } else {
//         val = [val];
//     }

//     if(val.length > 0) {
//         $.each(val,function(i,v){
//             var node = $.isPlainObject(v) ? oZtree{{ \$id }}.getNodeByParam(v.key,v.value):
//             oZtree{{ \$id }}.getNodeByParam("id",v);
//             if(node) {
//                 oZtree{{ \$id }}.checkNode(node, true, false, false);
//             }
//         });
//     }
// @endif

//      public function render()
//     {
//         $this->script = <<<EOT

// var editor = new wangEditor('{$this->id}');
//     editor.create();

// EOT;
//         return parent::render();

//     }
// }
