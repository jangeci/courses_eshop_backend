<?php

namespace App\Admin\Controllers;

use App\Models\Course;
use App\Models\CourseType;
use App\Models\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;

class CourseController extends AdminController
{
    protected function grid(){
        $grid = new Grid(new Course());
        return $grid;
    }

    protected function form(){
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
        $user = User::pluck('name', 'token');
        $form->select('user_token', __('Teacher'))->options($user);
        $form->display('created_at', __('Created at'));
        $form->display('updated_at', __('Updated at'));
        return $form;
    }
}
