<?php

namespace App\Models;

use Encore\Admin\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory, DefaultDatetimeFormat;

    protected $casts = [
        'video' => 'json'
    ];

    public function setVideoAttribute($value)
    {
        //array to json
        $this->attributes['video'] = json_encode(array_values($value));
    }

    public function getVideoAttribute($value)
    {
        $resVideo = json_decode($value, true)?: [];

//        if(!empty($resVideo)){
//            foreach ($resVideo as $k=>$v){
//                $resVideo[$k]['url'] = env('APP_URL').'uploads/'.$v['url'];
//                $resVideo[$k]['thumbnail'] = $v['thumbnail'];
//            }
//        }

        if(!empty($resVideo)){
            foreach ($resVideo as $k=>$v){
                $resVideo[$k]['url'] = $v['url'];
                $resVideo[$k]['thumbnail'] = $v['thumbnail'];
            }
        }

        return $resVideo;
    }
}
