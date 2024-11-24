<?php

namespace App\Admin\Controllers;

use App\Models\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class UserController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'User';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new User());

        $grid->column('id', __('Id'));
        $grid->column('token', __('Token'));
        $grid->column('name', __('Name'));
        $grid->column('email', __('Email'));
        $grid->column('email_verified_at', __('Email verified at'));
        $grid->column('password', __('Password'));
        $grid->column('remember_token', __('Remember token'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
        $grid->column('phone', __('Phone'));
        $grid->column('type', __('Type'));
        $grid->column('access_token', __('Access token'));
        $grid->column('avatar', __('Avatar'));
        $grid->column('deleted_at', __('Deleted at'));
        $grid->column('open_id', __('Open id'));

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
        $show = new Show(User::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('token', __('Token'));
        $show->field('name', __('Name'));
        $show->field('email', __('Email'));
        $show->field('email_verified_at', __('Email verified at'));
        $show->field('password', __('Password'));
        $show->field('remember_token', __('Remember token'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('phone', __('Phone'));
        $show->field('type', __('Type'));
        $show->field('access_token', __('Access token'));
        $show->field('avatar', __('Avatar'));
        $show->field('deleted_at', __('Deleted at'));
        $show->field('open_id', __('Open id'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new User());

        $form->text('token', __('Token'));
        $form->text('name', __('Name'));
        $form->text('email', __('Email'));
        $form->datetime('email_verified_at', __('Email verified at'))->default(date('Y-m-d H:i:s'));
        $form->text('password', __('Password'));
        $form->text('remember_token', __('Remember token'));
        $form->text('phone', __('Phone'));
        $form->text('type', __('Type'));
        $form->text('access_token', __('Access token'));
        $form->text('avatar', __('Avatar'));
        $form->text('open_id', __('Open id'));

        return $form;
    }
}
