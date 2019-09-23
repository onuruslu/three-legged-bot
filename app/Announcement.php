<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Events\AnkaraCompEng\AnnouncementCreatedEvent;
use App\Events\AnkaraCompEng\AnnouncementUpdatedEvent;

class Announcement extends Model
{
    protected $fillable	= [
		'title',
		'text',
		'link',
		'remote_id',
		'remote_updated_at',
		'remote_created_at',
    ];

    protected $dispatchesEvents	= [
    	'created'		=> AnnouncementCreatedEvent::class,
    	'updated'		=> AnnouncementUpdatedEvent::class,
    ];
}