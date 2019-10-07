<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Events\AnkaraCompEng\AnnouncementCreated;
use App\Events\AnkaraCompEng\AnnouncementUpdated;

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
    	'created'		=> AnnouncementCreated::class,
    	'updated'		=> AnnouncementUpdated::class,
    ];
}