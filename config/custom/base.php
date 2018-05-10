<?php

/**
 * @Author: tianwangchong
 * @Date:   2018-05-09 14:49:48
 * @Last Modified by:   tianwangchong
 * @Last Modified time: 2018-05-09 16:06:20
 */

// 所有的配置文件都使用下划线命名法

return [
	/**
	 * 上传有关的配置
	 */
    'upload' => [
        'image' => [
            // 图片上传大小的限制, 现在是1M(1024 * 1024)
            'max_size' => 1048576,
        ],
        'file'  => [
            // 文件上传大小的限制, 现在是10M
            'max_size' => 1048576 * 10,
        ],
    ],
];
