<?php

namespace App\Http\Controllers\Mall\Product;

use App\Classes\Upload\UploadImage;
use App\Http\Controllers\Controller;
use App\Http\Requests\Mall\Product\CategoryRequest;
use App\Models\Mall\Product\Category;
use App\Repositories\Mall\Product\CategoryRepository;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
use Response;
use Validator;

class CategoryController extends Controller
{
    use ModelForm;

    /** @var  CategoryRepository */
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepo)
    {
        $this->categoryRepository = $categoryRepo;
    }

    /**
     * Display a listing of the Category.
     *
     * @return Response
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header(__('mall.product.categories.index_header'));
            $content->description(__('mall.product.categories.index_description'));

            $content->body($this->grid());
        });
    }

    /**
     * Show the form for creating a new Category.
     *
     * @return Response
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header(__('mall.product.categories.create_header'));
            $content->description(__('mall.product.categories.create_description'));

            $content->body($this->form());

        });
    }

    /**
     * Store a newly created Category in storage.
     *
     * @param CategoryRequest $request
     *
     * @return Response
     */
    public function store(CategoryRequest $request)
    {
        $input = $request->all();
        $image = $request->file('logo');
        if ($image) {
            // 调用图片上传方法
            $result = UploadImage::uploadImage($image);

            if (is_array($result)) {
                Flash::error($result['error']);
                return back()->withInput();
            } else {
                $input['logo'] = '/' . $result;
            }
        }

        $category = $this->categoryRepository->create($input);

        Flash::success(__('mall.product.categories.saved_success'));

        return Input::get('_previous_') ? redirect(Input::get('_previous_')) : redirect(route('mall.product.categories.index'));

    }

    /**
     * Display the specified Category.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $category = $this->categoryRepository->findWithoutFail($id);

        if (empty($category)) {
            Flash::error(__('mall.product.categories.not_found'));

            return redirect(route('mall.product.categories.index'));
        }

        return view('mall.product.categories.show')->with('category', $category);

    }

    /**
     * Show the form for editing the specified Category.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header(__('mall.product.categories.edit_header'));
            $content->description(__('mall.product.categories.edit_description'));

            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Update the specified Category in storage.
     *
     * @param  int              $id
     * @param CategoryRequest $request
     *
     * @return Response
     */
    public function update($id, Request $request)
    {
        $category = $this->categoryRepository->findWithoutFail($id);

        if (empty($category)) {
            Flash::error(__('mall.product.categories.not_found'));

            return redirect(route('mall.product.categories.index'));
        }
        $input = $request->all();
        // 如果删除原图
        if (array_key_exists('_file_del_', $input)) {
            $imgUrl = $category->logo;
            // 根据配置得到根路径
            $rootDir = "";
            if (!$rootDir) {
                $rootDir = config('filesystems.disks.admin.root');
            }
            $fullPath = $rootDir . $imgUrl;
            @unlink($fullPath);
            $category->logo = null;
            $category->save();
            return response()->json(['message' => '删除成功', 'status' => true]);
        }
        $sValidators = [
            'name' => 'required|max:191',
        ];

        $validators = [];

        // 动态判断校验
        foreach ($input as $key => $value) {
            if (isset($sValidators[$key])) {
                $validators[$key] = $sValidators[$key];
            }
        }
        // 定义校验, 没有移到request
        $validator = Validator::make($request->all(), $validators, [
            // 'required' => ':attribute 不能为空',
        ], [
            // 'name'       => '名称',
        ]);

        if ($validator) {
            // 校验
            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }
        }

        $image = $request->file('logo');

        if ($image) {
            // // 允许上传的图片格式
            // $allowedExtensions = '';
            // // 图片上传路径, 默认传到 `laravel-admin` 下
            // $rootDir = '';
            // // 上传大小限制
            // $maxSize = '';
            // // 缩略图的尺寸
            // $resize = ['width' => '', 'height' => ''];
            // // 调用图片上传方法
            // $result = UploadImage::uploadImage($image, $rootDir, $allowedExtensions, $maxSize, $resize);
            
            $result = UploadImage::uploadImage($image);

            if (is_array($result)) {
                Flash::error($result['error']);
                return back()->withInput();
            } else {
                $input['logo'] = '/' . $result;
            }
        }

        $category = $this->categoryRepository->update($input, $id);

        Flash::success(__('mall.product.categories.updated_success'));

        return Input::get('_previous_') ? redirect(Input::get('_previous_')) : redirect(route('mall.product.categories.index'));
    }

    /**
     * Remove the specified Category from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        // 根据 `,` 判断传递过来的是单个id还是多个id
        if (substr_count($id, ',') >= 1) {
            $id = explode(",", $id);
        }

        // 如果是数组则进行批量删除
        if (is_array($id)) {
            if ($flag = $this->categoryRepository->batchDelete('id', $id)) {
                return response()->json(['message' => __('mall.product.categories.deleted_success'), 'status' => $flag]);
            } else {
                return response()->json(['message' => __('base.deleted.error'), 'status' => $flag]);
            }
        }

        $category = $this->categoryRepository->findWithoutFail($id);

        if (empty($category)) {
            Flash::error(__('mall.product.categories.not_found'));

            return redirect(route('mall.product.categories.index'));
        }

        if ($flag = $this->categoryRepository->delete($id)) {

            return response()->json(['message' => __('mall.product.categories.deleted_success'), 'status' => $flag]);
        } else {
            return response()->json(['message' => __('base.deleted.error'), 'status' => $flag]);
        }
    }

    /**
     * [form description]
     * @return {[type]} [description]
     */
    protected function form()
    {
        return Admin::form(Category::class, function (Form $form) {

            $form->display('id', 'ID');
            $form->text('name', '分类名称');
            $form->image('logo', '分类图片')->removable();
            // $categorys = Category::pluck('name', 'id');
            // $form->select('parent_id', '上级分类')->options($categorys);
            $categorys = Category::orderBy('order', 'asc')->get(['id', 'parent_id', 'name'])->toArray();
            $form->ztree('parent_id', '上级分类')->zTreeConfig(["map" => ["id" => "id", "name" => 'name', "parent_id" => "pId"], "type" => "radio", "open" => true])->options($categorys);
            $form->radio('status', '状态')->options(array(
                0 => '禁用',
                1 => '启用',
            ))->default('1');
            $form->number('order', '排序');
            $form->textarea('description', '描述');

            $form->display('created_at', __('base.created_at'));
            $form->display('updated_at', __('base.updated_at'));

        });
    }

    /**
     * [grid description]
     * @return {[type]} [description]
     */
    protected function grid()
    {

        return Admin::grid(Category::class, function (Grid $grid) {
            // 考虑是否需要scope和排序
            // $grid->model()->orderBy('listorder', 'asc');

            // // 添加按钮
            // if (!\Gate::check('mall.product.categories.create')) {
            //     $grid->disableCreation();
            // }

            // // 编辑和删除按钮
            // $grid->actions(function ($actions) {
            //     // 编辑按钮
            //     if (!\Gate::check('mall.product.categories.edit')) {
            //         $actions->disableEdit();
            //     }
            //     // 删除按钮
            //     if (!\Gate::check('mall.product.categories.destroy')) {
            //         $actions->disableDelete();
            //     }
            // });

            // // 导出按钮
            // if (!\Gate::check('mall.product.categories.export')) {
            //     $grid->disableExport();
            // }

            // // 批量操作
            // $grid->tools(function ($tools) {
            //     $tools->batch(function ($batch) {
            //         // 批量删除按钮
            //         if (!\Gate::check('mall.product.categories.batch_destroy')) {
            //             $batch->disableDelete();
            //         }
            //     });
            // });

            // 添加按钮
            if (Admin::user()->cannot('mall.product.categories.create')) {
                $grid->disableCreation();
            }

            // 编辑和删除按钮
            $grid->actions(function ($actions) {
                // 编辑按钮
                if (Admin::user()->cannot('mall.product.categories.edit')) {
                    $actions->disableEdit();
                }
                // 删除按钮
                if (Admin::user()->cannot('mall.product.categories.destroy')) {
                    $actions->disableDelete();
                }
            });

            // 导出按钮
            if (Admin::user()->cannot('mall.product.categories.export')) {
                $grid->disableExport();
            }

            // 批量操作
            $grid->tools(function ($tools) {
                $tools->batch(function ($batch) {
                    // 批量删除按钮
                    if (Admin::user()->cannot('mall.product.categories.batch_destroy')) {
                        $batch->disableDelete();
                    }
                });
            });

            $grid->column('id', 'ID')->sortable();
            $grid->column('name', '分类名称')->sortable();
            $grid->column('logo', '分类图片')->image();
            $grid->column('category.name', '上级分类')->sortable();
            $grid->column('status', '状态')->display(function ($val) {
                switch ($val) {
                    case 0:
                        return '<span class="badge bg-gray">禁用</span>';
                        break;

                    case 1:
                        return '<span class="badge bg-green">启用</span>';
                        break;
                }
            })->sortable();
            $grid->column('order', '排序')->sortable();
            $grid->column('description', '描述')->sortable();

            /**
             * 过滤处理
             */
            $grid->filter(function ($filter) {
                // // 禁用id查询框
                // $filter->disableIdFilter();

                // 分类名称
                $filter->like('name', '分类名称');
                $categorys = Category::pluck('name', 'id');
                // 上级分类
                $filter->in('parent_id', '上级分类')->select($categorys);
                // 状态
                $filter->equal('status', '状态')->radio([
                    0 => '禁用',
                    1 => '启用',
                ]);
            });
        });
    }
}
