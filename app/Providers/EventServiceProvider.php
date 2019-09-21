<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\AnkaraCompEng\AnnouncementCreatedEvent;
use App\Events\AnkaraCompEng\AnnouncementUpdatedEvent;
use App\Listeners\Telegram\AnkaraCompEng\SendAnnouncementCreatedMessage;
use App\Listeners\Telegram\AnkaraCompEng\SendAnnouncementUpdatedMessage;

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
        AnnouncementCreatedEvent::class => [
            SendAnnouncementCreatedMessage::class,
        ],
        AnnouncementUpdatedEvent::class => [
            SendAnnouncementUpdatedMessage::class,
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
