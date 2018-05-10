<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        // tian add `mapCustomRoutes`
        $this->mapCustomRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }

    /**
     * tian add
     * Define the "Custom" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapCustomRoutes()
    {
        /**
         * 加载 routes/web 文件夹下的路由
         */
        Route::group([
            // `laravel-admin` 有的中间件 'admin.auth', 'admin.pjax', 'admin.log', 'admin.bootstrap', 'admin.permission'
            'middleware' => ['web', 'admin', 'admin.bootstrap', 'admin.pjax', 'admin.log', 'admin.bootstrap', 'admin.permission'],
            'namespace'  => 'App\Http\Controllers',
            'prefix'     => 'admin',
        ], function ($router) {
            $routePath = dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'routes' . DIRECTORY_SEPARATOR . 'web';
            $this->getFilePath($routePath);
        });
    }

    /**
     * [getFilePath 递归遍历文件]
     * @param  string $path [description]
     * @return [type]       [description]
     */
    protected function getFilePath($path = '.')
    {
        // opendir()返回一个目录句柄, 失败返回false
        $current_dir = opendir($path);
        // readdir()返回打开目录句柄中的一个条目
        while (($file = readdir($current_dir)) !== false) {
            // 构建子目录路径
            $sub_dir = $path . DIRECTORY_SEPARATOR . $file;
            if ($file == '.' || $file == '..') {
                continue;
                // 如果是目录,进行递归
            } else if (is_dir($sub_dir)) {
                $this->getFilePath($sub_dir);
            } else {
                if (is_dir($sub_dir)) {
                    $this->getFilePath($sub_dir);
                }
                if (is_file($sub_dir)) {
                    require_once $sub_dir;
                }
                // 如果是文件,直接输出
                // $path = substr($path, strrpos($path, 'routes'));
                // echo base_path($path . DIRECTORY_SEPARATOR . $file) . '<br />';
                // require base_path($path . DIRECTORY_SEPARATOR . $file);
            }
        }
    }
}
