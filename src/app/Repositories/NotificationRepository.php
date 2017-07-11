<?php

namespace Shrizzer\Repositories;

use Shrizzer\Models\Notification;

/**
 * Class NotificationRepository
 */
class NotificationRepository
{
    /**
     * @param $type
     * @param $sessionId
     * @param null $urlId
     *
     * @return Notification
     */
    public function addNotification($type, $sessionId, $urlId = null)
    {
        $notification = new Notification();
        $notification->type = $type;
        $notification->session_id = $sessionId;
        $notification->url_id = $urlId;

        $this->save($notification);

        return $notification;
    }

    /**
     * @param Notification $notification
     */
    public function save(Notification $notification)
    {
        $notification->save();
    }
}