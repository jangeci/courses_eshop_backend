<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
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
}
