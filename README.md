## 环境+包

一共需要安装的包

```
// 不需要手动引入服务提供者
"arcanedev/log-viewer": "^4.3",
// 不需要手动引入服务提供者
"barryvdh/laravel-debugbar": "^2.4",
// 不需要手动引入服务提供者
"encore/laravel-admin": "1.4.*",
// 不需要手动引入服务提供者
"laracasts/flash": "^3.0",
// 不需要手动引入服务提供者
"predis/predis": "^1.1",
// 不需要手动引入服务提供者
"prettus/l5-repository": "^2.6",
// 不需要手动引入服务提供者
"zgldh/qiniu-laravel-storage": "^0.7.0"
// 不需要手动引入服务提供者
"spatie/laravel-backup": "^4.18",
// 不需要手动引入服务提供者
"intervention/image": "^2.4",
// 不需要手动引入服务提供者
"maatwebsite/excel": "^2.1",
// 直接安装
"doctrine/dbal": "^2.5",
// 直接安装
"spatie/eloquent-sortable": "^3.3",


/**
 * tian 引入 `axdlee/laravel-config-writer`【生成配置文件】-可以不要
 */
Axdlee\Config\ConfigServiceProvider::class,
"axdlee/laravel-config-writer": "^1.0",
"hassankhan/config": "^0.10.0",
```

### 初始化项目

```
laravel new 项目名
```

### 修改时区

`config/app.php` 里面修改时区

```
'timezone' => 'UTC',

改为

'timezone' => 'RPC',
```

### 将 `session` 存在 `redis` 中

- 安装包 `composer require predis/predis`

- 在 `config\database.php` 中的 `redis` 添加

```
// tian add session saved to redis
'session' => [
    'host'     => env('REDIS_HOST', 'localhost'),
    'password' => env('REDIS_PASSWORD', null),
    'port'     => env('REDIS_PORT', 6379),
    'database' => 1,
],
```

- 在 `config\session.php` 中修改

```
// tian add session saved to redis
// 'connection' => null,
'connection' => 'session',
```

- 在 `.env` 里面修改
```
# tian add session saved to redis
SESSION_DRIVER=redis
```

### 引入自定义类和函数

**引入自定义类**

在 `app` 目录下新建文件夹 `Classes`

本来是需要在 `composer.json` 里面引入, 但是因为是在 `app` 目录下面 `psr-4` 了所以不需要

```
    "autoload": {
        "classmap": [
            "database",
            "app/Classes"// 这行, 引入自定义类
        ],
        "psr-4": {
            "App\\": "app/"
        },
        "files": [
            "app/helpers.php"// 这行, 引入自定义函数
        ]
    },
```

**引入自定义函数**

在 `app` 目录下新建文件 `helpers.php`

### 使用 `laravel-admin`

- 安装 `composer require encore/laravel-admin`

- 然后运行下面的命令来发布资源：`php artisan vendor:publish --provider="Encore\Admin\AdminServiceProvider"`

- 自定义(重载) 修改默认的 9 张表, 在 `config/admin.php` 里的 `database` 的表名加上 `laravel_` 前缀.

- 防止执行数据迁移报错, 先解决字符长度问题

- 安装 `php artisan admin:install`

```
Migration table created successfully.
Migrating: 2014_10_12_000000_create_users_table
Migrated:  2014_10_12_000000_create_users_table
Migrating: 2014_10_12_100000_create_password_resets_table
Migrated:  2014_10_12_100000_create_password_resets_table
Migrating: 2016_01_04_173148_create_admin_tables
Migrated:  2016_01_04_173148_create_admin_tables
Admin directory was created: \app\Admin
HomeController file was created: \app\Admin/Controllers/HomeController.php
ExampleController file was created: \app\Admin/Controllers/ExampleController.php

Bootstrap file was created: \app\Admin/bootstrap.php
Routes file was created: \app\Admin/routes.php
```

- 配置图片图片上传, 配置 `config/filesystems.php`

```
'admin' => [
    'driver' => 'local',
    'root' => public_path('uploads'),
    'visibility' => 'public',
    'url' => env('APP_URL').'/uploads',
],
```

- 自定义视图目录

复制 `vendor/encore/laravel-admin/resources/views` 到项目的 `resources/views/laravel-admin`，然后在
 `app/Admin/bootstrap.php` 文件中加入代码：`app('view')->prependNamespace('admin', resource_path('views/laravel-admin'));`

- 自定义语言包

复制 `vendor/encore/laravel-admin/lang` 到项目的 `resources/lang/laravel-admin`，然后在 `app/Admin/bootstrap.php` 文件中加入代码：`app('translator')->addNamespace('admin', resource_path('lang/laravel-admin'));`

```
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
app('translator')->addNamespace('admin', resource_path('lang/laravel-admin'));
```

### `laravel-admin` 要做的修改:

#### 自定义(重载)

##### 修改默认的 `9` 张表

在 `config/admin.php` 里的 `database` 的表名加上 `laravel_` 前缀

##### [关于自定义视图](http://laravel-admin.org/docs/#/zh/qa?id=%e5%85%b3%e4%ba%8e%e8%87%aa%e5%ae%9a%e4%b9%89%e8%a7%86%e5%9b%be)

复制 `vendor/encore/laravel-admin/resources/views` 到项目的 `resources/views/laravel-admin`，然后在 `app/Admin/bootstrap.php` 文件中加入代码：

```
app('view')->prependNamespace('admin', resource_path('views/laravel-admin'));
```

这样就用 `resources/views/admin` 下的视图覆盖了 `laravel-admin` 的视图，要注意的问题是，更新 `laravel-admin` 的时候，如果遇到视图方面的问题，需要重新复制 `vendor/encore/laravel-admin/views` 到项目的 `resources/views/admin` 中，注意备份原来已经修改过的视图。

##### 设置语言

完成安装之后，默认语言为英文 `(en)` ，如果要使用中文，打开 `config/app.php`，将 `locale` 设置为 `zh-CN `即可。

##### [自定义语言](http://laravel-admin.org/docs/#/zh/qa?id=%e8%87%aa%e5%ae%9a%e4%b9%89%e8%af%ad%e8%a8%80)

如果需要修改 `laravel-admin` 的语言包，可以用下面的方式解决, 复制 `vendor/encore/laravel-admin/lang` 到项目的 `resources/lang/admin`，然后在 `app/Admin/bootstrap.php` 文件中加入代码：

```
app('translator')->addNamespace('admin', resource_path('lang/admin'));
```

如果将系统语言 `locale` 设置为 `zh-CN`，可以将 `resources/lang/admin` 目录下的 `zh_CN` 目录重命名为 `zh-CN` 即可，更新 `laravel-admin` 的时候，要做相应修改。

##### [更新静态资源]([http://laravel-admin.org/docs/#/zh/qa?id=%e6%9b%b4%e6%96%b0%e9%9d%99%e6%80%81%e8%b5%84%e6%ba%90](http://laravel-admin.org/docs/#/zh/qa?id=%e6%9b%b4%e6%96%b0%e9%9d%99%e6%80%81%e8%b5%84%e6%ba%90))

如果遇到更新之后,部分组件不能正常使用,那有可能是 `laravel-admin` 自带的静态资源有更新了,所以需要手动去用 `vendor/encore/laravel-admin/assets` 的静态资源覆盖掉 `public/packages` 目录下的静态资源文件,覆盖完成之后不要忘记清理浏览器缓存.

##### [自定义登陆页面和登陆逻辑]([http://laravel-admin.org/docs/#/zh/qa?id=%e8%87%aa%e5%ae%9a%e4%b9%89%e7%99%bb%e9%99%86%e9%a1%b5%e9%9d%a2%e5%92%8c%e7%99%bb%e9%99%86%e9%80%bb%e8%be%91](http://laravel-admin.org/docs/#/zh/qa?id=%e8%87%aa%e5%ae%9a%e4%b9%89%e7%99%bb%e9%99%86%e9%a1%b5%e9%9d%a2%e5%92%8c%e7%99%bb%e9%99%86%e9%80%bb%e8%be%91))

在路由文件 `app/Admin/routes.php` 中，覆盖掉登陆页面和登陆逻辑的路由，即可实现自定义的功能

```
Route::group([
    'prefix'        => config('admin.prefix'),
    'namespace'     => Admin::controllerNamespace(),
    'middleware'    => ['web', 'admin'],
], function (Router $router) {

    $router->get('auth/login', 'AuthController@getLogin');
    $router->post('auth/login', 'AuthController@postLogin');

});
```

在自定义的路由器 `AuthController` 中的 `getLogin`、`postLogin` 方法里分别实现自己的登陆页面和登陆逻辑。

### 使用日志包 `arcanedev/log-viewer`

- `composer require arcanedev/log-viewer`

- 自动引入服务提供者, 所以不需要添加 `providers`

- `php artisan log-viewer:publish`

```
   __                   _
  / /  ___   __ _/\   /(_) _____      _____ _ __
 / /  / _ \ / _` \ \ / / |/ _ \ \ /\ / / _ \ '__|
/ /__| (_) | (_| |\ V /| |  __/\ V  V /  __/ |
\____/\___/ \__, | \_/ |_|\___| \_/\_/ \___|_|
            |___/

Version 4.5.1 - Created by ARCANEDEV�

Copied File [\vendor\arcanedev\log-viewer\config\log-viewer.php] To [\config\log
-viewer.php]
Copied Directory [\vendor\arcanedev\log-viewer\resources\views] To [\resources\v
iews\vendor\log-viewer]
Copied Directory [\vendor\arcanedev\log-viewer\resources\lang] To [\resources\la
ng\vendor\log-viewer]
Publishing complete.
```

- 修改日志级别

修改 `.env` 中的 `LOG_CHANNEL=stack` 改为为 `daily`

```
laravel5.5及之前:

'log' => env('APP_LOG', 'single'),

'log_level' => env('APP_LOG_LEVEL', 'debug'),
```

- 修改语言

将语言修改为中文,在 `config/log-viewer.php` 中的 `locale='auto'` 修改为 `locale='zh'`

- 修改路由和中间件

```
    'route'         => [
        'enabled'    => true,

        'attributes' => [
            // 'prefix'     => 'log-viewer',
            // 修改路由前缀
            'prefix'     => 'admin/log-viewer',

            // 'middleware' => env('ARCANEDEV_LOGVIEWER_MIDDLEWARE') ? explode(',', env('ARCANEDEV_LOGVIEWER_MIDDLEWARE')) : null,
            // 修改中间件, 或者直接在 `.env` 里面改为：
ARCANEDEV_LOGVIEWER_MIDDLEWARE=web,admin,admin.bootstrap,admin.pjax,admin.log,admin.bootstrap,admin.permission
            'middleware' => ['web', 'admin', 'admin.bootstrap', 'admin.pjax', 'admin.log', 'admin.bootstrap', 'admin.permission'],
        ],
    ],
```

- 访问

原链接: `http://127.0.0.1:8000/log-viewer`

改路由前缀后: `http://127.0.0.1:8000/admin/log-viewer`

### 使用调试包 `barryvdh/laravel-debugbar`

- `composer require barryvdh/laravel-debugbar`

- `Barryvdh\Debugbar\ServiceProvider::class,`【不需要了, 自动引入服务提供者】

- `'Debugbar' => Barryvdh\Debugbar\Facade::class,`【不需要 `alias`, 自动引入了】

- `php artisan vendor:publish --provider="Barryvdh\Debugbar\ServiceProvider"`

```
Copied File [\vendor\barryvdh\laravel-debugbar\config\debugbar.php] To [\config\
debugbar.php]
Publishing complete.
```

- 非调试环境下,关闭debug,将 `.env=true` 改为 `.env=false` ,默认debug是开启状态.

### 数据库备份 `spatie/laravel-backup`

- `composer require spatie/laravel-backup`

- 自动引入服务提供者

- `php artisan vendor:publish --provider="Spatie\Backup\BackupServiceProvider"`

```
Copied File [\vendor\spatie\laravel-backup\config\backup.php] To [\config\backup
.php]
Copied Directory [\vendor\spatie\laravel-backup\resources\lang] To [\resources\l
ang\vendor\backup]
Publishing complete.
```

- 用这个包, 需要安装 `composer require guzzlehttp/guzzle` , 因为需要用到请求

- 如果要备份到 自定义目录 可以进行一下配置

**app/config/filesystems.php:**

```
'disks'   => [

    // 添加laravel-backup备份文件目录
    'backup' => [
        'driver'     => 'local',
        'root'       => env('BACKUP_PATH'),
        'visibility' => 'public',
    ], 
],
```

**app/config/laravel-backup.php:**

```
'destination' => [

	/*
	* The disk names on which the backups will be stored. 
	*/
	'disks' => [
	    // 'local',
	    'backup',
	    //'admin',
	],
],
```

- 命令说明

如果要备份到特定磁盘而不是所有磁盘，请运行：

```
php artisan backup:run --only-to-disk=name-of-your-disk
```

备份文件和数据库:

```
php artisan backup:run
```

只备份db:

```
php artisan backup:run --only-db
```

只备份文件:

```
php artisan backup:run --only-files
```

清理备份:

```
php artisan backup:clean
```

查看所有受监视的目标文件系统的状态:

```
php artisan backup:list
```

```
    'monitorBackups' => [
        [
            'name' => config('app.name'),
            'disks' => ['local'],
            'newestBackupsShouldNotBeOlderThanDays' => 1,
            'storageUsedMayNotBeHigherThanMegabytes' => 5000,
        ],

        /*
        [
            'name' => 'name of the second app',
            'disks' => ['local', 's3'],
            'newestBackupsShouldNotBeOlderThanDays' => 1,
            'storageUsedMayNotBeHigherThanMegabytes' => 5000,
        ],
        */
    ],
```

### 引入自定义路由-(因为代码是自动吐出的, 路由是分目录的)

加载 `routes/web` 文件夹下的路由

在 `app/Providers/RouteServiceProvider.php` 中

`map()` 方法中,添加以下代码:

```
// tian add `mapCustomRoutes`
$this->mapCustomRoutes();
```

新增以下方法:

```
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
```

### 安装数据库抽象层 `prettus/l5-repository`【对模型的一层封装】

- `composer require prettus/l5-repository`

- 自动引入服务提供者

- `php artisan vendor:publish --provider "Prettus\Repository\Providers\RepositoryServiceProvider"`

```
Copied File [\vendor\prettus\l5-repository\src\resources\config\repository.php]
To [\config\repository.php]
Publishing complete.
```

### 安装提示包 `laracasts/flash`【提示框】

- `composer require laracasts/flash`

- 需要引入服务提供者【现在不需要了, laravel5.5后自动引入服务提供者】

- 需要在 `config/app.php` 里的 `aliases` 加入 `Facades`【不需要 `alias`, 自动引入了】

将提示信息可以显示在 `laravel-admin` 框架中

在 `resources\views\laravel-admin\content.blade.php` 里的 `<section class="content">` 下添加如下代码:

```
{{-- tian add flash --}}
@include('flash::message')
```

### doctrine/dbal【使用 `migration` 作为数据库的版本控制工具，当需要对已存在的数据表作更改，需要额外引入 `doctrine/dbal` 扩展。】

- `composer require doctrine/dbal`

### 安装图像处理包 `intervention/image`

- `composer require intervention/image`

- 自动引入服务提供者

- `php artisan vendor:publish --provider="Intervention\Image\ImageServiceProviderLaravel5"`

```
Copied File [\vendor\intervention\image\src\config\config.php] To [\config\image
.php]
Publishing complete.
```

### 请求 `guzzlehttp/guzzle`【发送http请求】

- `composer require guzzlehttp/guzzle`

### 七牛云 `zgldh/qiniu-laravel-storage`【选择性安装】

- `composer require zgldh/qiniu-laravel-storage`

- 自动引入服务提供者

### excel处理 `maatwebsite/excel`【选择性安装】

- `composer require maatwebsite/excel`

- 自动引入服务提供者

- `php artisan vendor:publish --provider="Maatwebsite\Excel\ExcelServiceProvider"`

### 模型排序 `spatie/eloquent-sortable`【选择性安装】

- `composer require spatie/eloquent-sortable`



--------------------------------------



## 问题

**问题1:** 执行 `php artisan migrate` 后报错 `SQLSTATE[42000]: Syntax error or access violation: 1071 Specified key was too long; max key length is 767 bytes`

问题分析: `MySql` 支持的 `utf8` 编码最大字符长度为3字节，如果遇到4字节的宽字符就会出现插入异常。三个字节UTF-8最大能编码的Unicode字符是 `0xffff`，即Unicode中的基本多文种平面（BMP）。因而包括 `Emoji` 表情（Emoji是一种特殊的Unicode编码）在内的非基本多文种平面的Unicode字符都无法使用MySql的utf8字符集存储。

这也应该就是 Laravel 5.4 改用 4 字节长度的 `utf8mb4` 字符编码的原因之一。不过要注意的是，只有MySql 5.5.3版本以后才开始支持 `utf8mb4` 字符编码（查看版本-mysql命令：`SELECT VERSION( )`）

**解决方案:** 在 `AppServiceProvider` 中调用 `Schema::defaultStringLength` 方法来实现配置

```
use Illuminate\Support\Facades\Schema;

/**
* Bootstrap any application services.
*
* @return void
*/
public function boot()
{
   Schema::defaultStringLength(191);
}
```

- **问题2**: 使用 `laravel new yunjuji-generator` 报错 `Script "post-install-cmd" is not defined in this package`

**解决方案:** 问题不解决也可以， 如需解决请执行 `composer global update` 或者 `composer global require "laravel/installer"`
