<?php

namespace App\Services\AnkaraCompEng\ScanningLessonPages\Entities;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    public function semester(){
    	return $this->belongsTo(Semester::class, 'semester_id');
    }

    public function lessons(){
    	return $this->hasMany(Lesson::class, 'level_id');
    }
}
