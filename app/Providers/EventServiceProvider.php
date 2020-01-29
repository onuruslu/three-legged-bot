<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\AnkaraCompEng\AnnouncementCreated;
use App\Events\AnkaraCompEng\AnnouncementUpdated;
use App\Events\AnkaraCompEng\LessonPageCreated;
use App\Listeners\Telegram\AnkaraCompEng\SendAnnouncementCreatedMessage;
use App\Listeners\Telegram\AnkaraCompEng\SendAnnouncementUpdatedMessage;
use App\Listeners\Telegram\AnkaraCompEng\SendLessonPageChangedMessage;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        AnnouncementCreated::class => [
            SendAnnouncementCreatedMessage::class,
        ],
        AnnouncementUpdated::class => [
            SendAnnouncementUpdatedMessage::class,
        ],
        LessonPageCreated::class => [
            SendLessonPageChangedMessage::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
