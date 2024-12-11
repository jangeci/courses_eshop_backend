<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use Exception;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function courseLessons(Request $request)
    {
        $id = $request->id;

        try {
            $result = Lesson::where('course_id', $id)->select(
                'id',
                'name',
                'description',
                'thumbnail',
                'video',
            )->get();

            return response()->json([
                'code' => 200,
                'msg' => 'Course Lessons',
                'data' => $result
            ], 200);

        } catch (Exception $exception) {
            return response()->json([
                'code' => 500,
                'msg' => 'Server internal error',
                'data' => $exception->getMessage()
            ], 500);
        }
    }
}
