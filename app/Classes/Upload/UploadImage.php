<?php

namespace App\Classes\Upload;

use Image;

class UploadImage
{
    /**
     * [图片上传 description]
     * @return [type] [description]
     */
    public static function uploadImage($image, $rootDir = '', $allowedExtensions = ["png", "jpg", "gif", "jpeg"], $maxSize = '', $resize = [])
    {
        if ($image) {
            if ($image->isValid()) {
                // 判断allowedExtensions是否为数组
                if (empty($allowedExtensions) || !is_array($allowedExtensions)) {
                    return ['error' => '图片上传控件的参数:allowedExtensions应该为数组并且不能为空!'];
                }

                // 判断图片格式是否符合指定格式
                if ($image->getClientOriginalExtension() && !in_array($image->getClientOriginalExtension(), $allowedExtensions)) {
                    return ['error' => '支持的图片格式为' . implode(', ', $allowedExtensions)];
                }

                // 判断图片是否超过大小限制
                $size = $image->getSize();
                if (empty($maxSize)) {
                    $maxSize = config('custom.base.upload.image.max_size');
                }
                if ($size > $maxSize) {
                    return ['error' => '图片大小只能为' . intval($maxSize / 1048576) . 'M'];
                }

                // 临时文件的绝对路径
                $realPath = $image->getRealPath();

                // 是否需要缩放图片
                if (isset($resize['width']) && isset($resize['height']) && !empty($resize['width']) && !empty($resize['height'])) {
                    $img = Image::make($realPath)->resize($resize['width'], $resize['height']);
                } else {
                    $img = Image::make($realPath);
                }

                // 获得图片的扩展名
                $extension = $image->getClientOriginalExtension();

                // 重新命名上传文件名字
                $newImageName = md5(time()) . random_int(1000, 9999) . "." . $extension;

                // 图片上传路径, 默认传到 `laravel-admin下`
                if (!$rootDir) {
                    $rootDir = config('filesystems.disks.admin.root');
                }

                // 图片的完整路径
                $fullPath = $rootDir . '/' . $newImageName;

                // 保存图片
                $bool = $img->save($fullPath);

                // 返回图片的路径
                return $newImageName;
            }
        }
    }

    // 使用系统自带的Storage保存图片
    // public function updateStore(Request $request)
    // {
    //     // use Illuminate\Support\Facades\Storage;
    //     // php artisan storage:link
    //     // 使用laravel 自带的request类来获取一下文件
    //     $wenjian = $request->file('file');
    //     // 文件是否上传成功
    //     if ($wenjian->isValid()) {

    //         // 获取文件的原文件名 包括扩展名
    //         $yuanname = $wenjian->getClientOriginalName();

    //         // 获取文件的扩展名
    //         $kuoname = $wenjian->getClientOriginalExtension();

    //         // 获取文件的类型
    //         $type = $wenjian->getClientMimeType();

    //         // 获取文件的绝对路径，但是获取到的在本地不能打开
    //         $path = $wenjian->getRealPath();

    //         //要保存的文件名 时间+扩展名
    //         $filename = date('Y-m-d-H-i-s') . '_' . uniqid() . '.' . $kuoname;
    //         //保存文件          配置文件存放文件的名字  ，文件名，路径
    //         $bool = Storage::disk('upload')->put($filename, file_get_contents($path));
    //         return back();
    //     }
    // }
}
