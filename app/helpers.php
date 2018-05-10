<?php

/**
 * @Author: tianwangchong
 * @Date:   2018-02-01 10:36:14
 * @Last Modified by:   tianwangchong
 * @Last Modified time: 2018-05-09 14:39:09
 */

if (!function_exists('make_sms_code')) {
    /**
     * [makeCode 随机字符串]
     * @param  integer $length [随机数长度]
     * @return [type]          [返回一个指定长度的字符串]
     */
    function make_sms_code($length = 5)
    {
        // 密码字符集，可任意添加你需要的字符
        $chars = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
        // 在 $chars 中随机取 $length 个数组元素键名
        $keys = array_rand($chars, $length);
        $code = '';
        for ($i = 0; $i < $length; $i++) {
            // 将 $length 个数组元素连接成字符串
            $code .= $chars[$keys[$i]];
        }
        return $code;
    }
}

if (!function_exists('is_mobile')) {
    /**
     * [isMobile 验证手机号是否正确]
     * @param  [type]  $mobile [description]
     * @return boolean         [description]
     */
    function is_mobile($mobile)
    {
        //"/^1[34578]\d{9}$/"; // "^"符号表示必须是1开头; "[ ]"的意思是第二个数字必须是中括号中一个数字; 而 \d 则表示0-9任意数字,后跟{9}表示长度是9个数字; 后面的$表示结尾; 开始和结尾的 / 是正则表达式必须放在这个中间, 有的后面可能还跟模式.
        if (!is_numeric($mobile)) {
            return false;
        }
        return preg_match('#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$#', $mobile) ? true : false;
    }
}