<?php

namespace App\Services\AnkaraCompEng\ScanningLessonPages\Entities;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Lesson extends Model
{
	protected $fillable	= ['title', 'link'];
	
    public function level(){
    	return $this->belongsTo(Level::class, 'level_id');
    }

    public function history(){
        return $this->hasMany(LessonPage::class, 'lesson_id');
    }

    public function lastPage(){
    	return $this->history()->orderBy('id', 'desc')->first();
    }

    public function users(){
        return $this->belongsToMany(User::class);
    }
}
