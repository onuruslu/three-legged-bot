<?php

namespace App\Services\AnkaraCompEng\ScanningLessonPages\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Events\AnkaraCompEng\LessonPageCreated;

class LessonPage extends Model
{
	protected $fillable	= ['text', 'diff'];
    protected $casts = [
        'diff' => 'array',
    ];

    protected $dispatchesEvents = [
        'created' => LessonPageCreated::class,
    ];
	
    public function lesson(){
    	return $this->belongsTo(Lesson::class, 'lesson_id');
    }

    public function previous(int $sequence = 1){
    	$query	= $this->orderBy('id', 'desc')
    		->where([
    			['lesson_id', $this->lesson_id],
    			['id', '<', $this->id],
    		]);

    	if($sequence!==1)
    		$query	= $query->skip($sequence - 1);

    	return $query->first();
    }

    public function next(int $sequence = 1){
    	return $this->orderBy('id')
    		->where([
    			['lesson_id', $this->lesson_id],
    			['id', '>', $this->id],
    		]);

    	if($sequence!==1)
    		$query	= $query->skip($sequence - 1);

    	return $query->first();
    }
}
