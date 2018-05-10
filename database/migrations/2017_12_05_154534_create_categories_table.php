<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCategoriesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('categories')) {
            Schema::table('categories', function (Blueprint $table) {
                if (Schema::hasColumn('categories', 'id')) {

                } else {
                    $table->increments('id');
                }
                if (Schema::hasColumn('categories', 'name')) {
                    $table->string('name', 191)->comment('分类名称')->change();
                } else {
                    $table->string('name', 191)->index()->comment('分类名称');
                }
                if (Schema::hasColumn('categories', 'logo')) {
                    $table->string('logo', 191)->nullable()->comment('分类图片')->change();
                } else {
                    $table->string('logo', 191)->nullable()->comment('分类图片');
                }
                if (Schema::hasColumn('categories', 'parent_id')) {
                    $table->integer('parent_id')->nullable()->default(0)->comment('上级分类')->change();
                } else {
                    $table->integer('parent_id')->nullable()->default(0)->comment('上级分类');
                }
                if (Schema::hasColumn('categories', 'status')) {
                    $table->tinyInteger('status')->default(1)->comment('状态')->change();
                } else {
                    $table->tinyInteger('status')->default(1)->comment('状态');
                }
                if (Schema::hasColumn('categories', 'order')) {
                    $table->integer('order')->unsigned()->default(0)->comment('排序')->change();
                } else {
                    $table->integer('order')->unsigned()->default(0)->comment('排序');
                }
                if (Schema::hasColumn('categories', 'description')) {
                    $table->text('description')->nullable()->comment('描述')->change();
                } else {
                    $table->text('description')->nullable()->comment('描述');
                }
            });
        } else {
            Schema::create('categories', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', 191)->index()->comment('分类名称');
                $table->string('logo', 191)->nullable()->comment('分类图片');
                $table->integer('parent_id')->nullable()->default(0)->comment('上级分类');
                $table->tinyInteger('status')->default(1)->comment('状态');
                $table->integer('order')->unsigned()->default(0)->comment('排序');
                $table->text('description')->nullable()->comment('描述');
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
