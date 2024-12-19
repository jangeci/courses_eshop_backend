<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class VideoController extends Controller
{
    public function streamVideo($filename){
        $filePath = public_path('/uploads/files/' . $filename);

        if (!file_exists($filePath)) {
            abort(404, 'Video file not found');
        }

        $stream = function () use ($filePath) {
            $file = fopen($filePath, 'rb');

            $size   = filesize($filePath);
            $length = $size;
            $start  = 0;
            $end    = $size - 1;

            // Check if the client has requested a range
            if (isset($_SERVER['HTTP_RANGE'])) {
                $range = $_SERVER['HTTP_RANGE'];
                $range = explode('=', $range, 2)[1];
                $range = explode('-', $range);

                $start = intval($range[0]);
                if (isset($range[1]) && is_numeric($range[1])) {
                    $end = intval($range[1]);
                }

                $length = $end - $start + 1;
                fseek($file, $start);
            }

            header('Content-Type: video/mp4');
            header('Content-Length: ' . $length);
            header('Accept-Ranges: bytes');
            header("Content-Range: bytes $start-$end/$size");

            while (!feof($file) && ($p = ftell($file)) <= $end) {
                echo fread($file, 1024 * 8);
                flush();
            }

            fclose($file);
        };

        return response()->stream($stream, 200, [
            'Content-Type'  => 'video/mp4',
            'Accept-Ranges' => 'bytes',
        ]);
    }
}
