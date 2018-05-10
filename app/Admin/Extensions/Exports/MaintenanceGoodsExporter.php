<?php
namespace App\Admin\Extensions\Exports;

use Encore\Admin\Grid\Exporters\AbstractExporter;

class MaintenanceGoodsExporter extends AbstractExporter
{
    public function export()
    {
        // 导出的文件名
        // $filename = $this->getTable().'.csv';
        $filename = '维修类商品-' . date('YmdHis') . '.csv';
        // 表头
        $titles = ['编号', '名称', '价格', '图片', '描述'];
        // 获取数据
        $data = $this->getData();
        // if (!empty($data)) {
        //     // 将多维数组转化为一维数组
        //     $columns = array_dot($this->sanitize($data[0]));
        //     // 获得key
        //     $titles = array_keys($columns);
        // }

        // 根据上面的数据拼接出导出数据，
        $output = '';
        $output = implode(',', $titles) . "\n";
        // 遍历数据
        foreach ($data as $row) {
            $row1 = [
                // ID
                $row['goods_no'], 
                // 名称
                $row['name'],
                // 价格
                $row['price'],
                // 图片
                $row['image'],
                // 描述
                $row['description']

            ];
            $output .= implode(',', $row1) . "\n";

            // 获得指定的列
            // $row = array_only($row, $titles);
            // $output .= implode(',', array_dot($row))."\n";
        }

        // 在这里控制你想输出的格式,或者使用第三方库导出Excel文件
        $headers = [
            'Content-Encoding'    => 'UTF-8',
            'Content-Type'        => 'text/csv;charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        // 多加这一段解决乱码, 导出csv文件用excel打开乱码怎么解决, 用记事本另存ansi编码之后正常
        // echo "\xEF\xBB\xBF";

        // 导出文件，
        response(rtrim($output, "\n"), 200, $headers)->send();

        exit;
    }
}

