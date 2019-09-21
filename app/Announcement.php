<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
}
