<?php

namespace App\Services\AnkaraCompEng\ScanningLessonPages\Entities;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    public function level(){
    	return $this->belongsTo(Lesson::class, 'level_id');
    }
}
