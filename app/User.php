<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Services\AnkaraCompEng\ScanningLessonPages\Entities\Lesson;

class User extends Authenticatable
{
    use Notifiable;

    CONST TELEGRAM_MAIL_DOMAIN = 'telegram.com';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'telegram_id', 'telegram_username', 'telegram_language_code'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function setTelegramIdAttribute($value)
    {
        $this->attributes['telegram_id'] = $value;
        $this->attributes['email'] = $value . '@' . self::TELEGRAM_MAIL_DOMAIN;
    }

    public function lessons(){
        return $this->belongsToMany(Lesson::class);
    }
}
