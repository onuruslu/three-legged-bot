<?php

namespace App\Services\AnkaraCompEng\ScanningLessonPages\Entities;

use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
	protected $fillable	= ['title', 'link'];
	
	public function levels(){
		return $this->hasMany(Level::class, 'semester_id');
	}
}
