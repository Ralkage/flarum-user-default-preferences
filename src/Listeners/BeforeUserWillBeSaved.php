<?php

namespace PT\Preferences\Listeners;

use Flarum\Notification\NotificationSyncer;
use Flarum\User\User;
use Flarum\User\Event\Saving;
use Illuminate\Contracts\Events\Dispatcher;

class BeforeUserWillBeSaved
{
    const USER_PREFERENCES = [
        "email" => [
            'postMentioned' => true,
            'userMentioned' => true
        ],
    ];
    /**
     * @var NotificationSyncer
     */
    protected $notifications;

    /**
     * @param NotificationSyncer $notifications
     */
    public function __construct(NotificationSyncer $notifications)
    {
        $this->notifications = $notifications;
    }

    /**
     * @param Dispatcher $events
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(Saving::class, [$this, 'beforeUserWillBeSaved']);
    }

    /**
     * @param Saving $event
     */
    public function beforeUserWillBeSaved(Saving $event)
    {
        /** @var User $user */
        $user = $event->user;
        if ($user->exists) {
            return;
        }
        foreach (self::getDefaultUserPreferences() as $key => $value) {
            $user->setPreference($key, $value);
        }
    }

    /**
     * @return array
     */
    public static function getDefaultUserPreferences()
    {
        $preferences = [];
        foreach (self::USER_PREFERENCES as $method => $types) {
            foreach ($types as $type => $value) {
                $preferences [User::getNotificationPreferenceKey($type, $method)] = $value;
            }
        }
        return $preferences;
    }
}
