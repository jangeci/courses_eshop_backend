<?php

namespace App\Admin\Controllers;

use App\Models\Course;
use App\Models\CourseType;
use App\Models\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\DB;

class CourseController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Course';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Course());

        if (Admin::user()->isRole('teacher')) {
            $token = Admin::user()->token;
            $grid->model()->where('user_token', $token);
        }

        $grid->column('id', __('Id'));
        $grid->column('user_token', __('Teachers'))->display(
            function ($token) {
                return DB::table("admin_users")->where('token', $token)->value('username');
            });
        $grid->column('name', __('Name'));
        $grid->column('thumbnail', __('Thumbnail'))->image('', 50, 50);
        $grid->column('description', __('Description'));
        $grid->column('type_id', __('Type id'));
        $grid->column('price', __('Price'));
        $grid->column('lesson_count', __('Lesson count'));
        $grid->column('video_length', __('Video length'));
        $grid->column('created_at', __('Created at'));

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Course::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('user_token', __('User token'));
        $show->field('name', __('Name'));
        $show->field('thumbnail', __('Thumbnail'));
        $show->field('video', __('Video'));
        $show->field('description', __('Description'));
        $show->field('type_id', __('Type id'));
        $show->field('price', __('Price'));
        $show->field('lesson_count', __('Lesson count'));
        $show->field('video_length', __('Video length'));
        $show->field('follow', __('Follow'));
        $show->field('score', __('Score'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Course());
        $form->text('name', __('Name'));
        $options = CourseType::pluck('title', 'id');
        $form->select('type_id', __('Category'))->options($options);
        $form->image('thumbnail', __('Thumbnail'))->uniqueName();
        $form->file('video', __('Video'))->uniqueName();
        $form->textarea('description', __('Description'));
        $form->decimal('price', __('Price'));
        $form->number('lesson_count', __('Lesson count'));
        $form->number('video_length', __('Video length'));
        //for who is posting course
        if (Admin::user()->isRole('teacher')) {
            $token = Admin::user()->token;
            $username = Admin::user()->username;
            $result = User::pluck('name', 'token');
            $form->select('user_token', __('Teacher'))->options([$token => $username])->default($token)->readonly();
        } else {
            $users = DB::table('admin_users')->pluck('name', 'token');
            $form->select('user_token', __('Teacher'))->options($users);
        }
        $form->display('created_at', __('Created at'));
        $form->display('updated_at', __('Updated at'));
        $form->switch('recommended', __('Recommended'));
        return $form;
    }
}
