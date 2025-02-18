<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Order;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function courseList()
    {
        $result = Course::select('name', 'id', 'thumbnail', 'lesson_count', 'price')->get();
        return response()->json([
            'code' => 200,
            'msg' => 'Course List',
            'data' => $result], 200);
    }

    public function courseDetail(Request $request)
    {
        $id = $request->id;

        try {
            $result = Course::where('id', $id)->select(
                'id',
                'name',
                'description',
                'thumbnail',
                'lesson_count',
                'price',
                'user_token',
                'video_length')->first();

            return response()->json([
                'code' => 200,
                'msg' => 'Course detail',
                'data' => $result], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'code' => 500,
                'msg' => 'Server internal error',
                'data' => $th->getMessage()], 500);
        }
    }

    public function coursesBought(Request $request)
    {
        $user = $request->user();
        $map = [];
        $map['status'] = 1;
        $map['user_token'] = $user->token;
        $courseIds = Order::where($map)->select('course_id')->get();
        $result = Course::whereIn('id', $courseIds)->select('name', 'id', 'thumbnail', 'lesson_count', 'price')->get();

        return response()->json([
            'code' => 200,
            'msg' => 'The courses you have bought',
            'data' => $result], 200);
    }

    public function coursesRecommended()
    {
        $result = Course::where('recommended', 1)->select('name', 'id', 'thumbnail', 'lesson_count', 'price')->get();

        return response()->json([
            'code' => 200,
            'msg' => 'Recommended courses',
            'data' => $result], 200);
    }

    public function coursesSearch(Request $request)
    {
        $search = $request->search;
        $result = Course::where('name', 'like', '%' . $search . '%')->select('name', 'id', 'thumbnail', 'lesson_count', 'price')->get();

        return response()->json([
            'code' => 200,
            'msg' => 'Searched courses',
            'data' => $result], 200);
    }
}
