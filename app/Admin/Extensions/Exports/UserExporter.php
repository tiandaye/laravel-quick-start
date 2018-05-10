<?php
namespace App\Admin\Extensions\Exports;

use Encore\Admin\Grid\Exporters\AbstractExporter;

class UserExporter extends AbstractExporter
{
    public function export()
    {
        // 导出的文件名
        // $filename = $this->getTable().'.csv';
        $filename = '用户-' . date('YmdHis') . '.csv';
        // 表头
        $titles = ['工号', '用户名', '真实姓名', '手机号', '身份证号', '网格', '职位', '部门', '头像', '角色'];
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
        	$role = '';
        	$post = '';
            $department = '';
        	$grid = '';
            // 角色
        	if ($row['roles']) {
        		$roles = array_column($row['roles'], 'name');
        		$role = implode('/', $roles);
        	}
            // 网格
        	if ($row['grid'] && $row['grid']['name']) {
        		$grid = $row['grid']['name'];
        	}
            // 职位
            if ($row['post'] && $row['post']['title']) {
                $post = $row['post']['title'];
            }
            // 部门
        	if ($row['department'] && $row['department']['title']) {
        		$department = $row['department']['title'];
        	}
            $row1 = [
                // ID
                $row['worker_no'], 
                // 用户名
                $row['username'], 
                // 真实姓名
                $row['name'], 
                // 手机号
                $row['phone'], 
                // 身份证号
                $row['id_card'], 
                // 网格
                $grid,
                // 职位
                $post,
                // 部门
                $department,
                // 头像
                $row['avatar'],
                // 角色
                $role
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

