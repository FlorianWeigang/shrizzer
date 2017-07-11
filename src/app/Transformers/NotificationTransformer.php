<?php

namespace Shrizzer\Transformers;

use League\Fractal\TransformerAbstract;
use Shrizzer\Models\Notification;
use Shrizzer\Models\SessionUrl;

class NotificationTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $defaultIncludes = [
        'session',
        'sessionUrl'
    ];

    /**
     * A Fractal transformer.
     *
     * @param Notification $notification
     *
     * @return array
     */
    public function transform(Notification $notification)
    {
        return [
            'id' => $notification->id,
            'type' => $notification->type,
            'createdAt' => $notification->created_at->format('H:i:s d.m.Y')
        ];
    }

    /**
     * Include Author
     *
     * @param Notification $notification
     *
     * @return \League\Fractal\Resource\Item
     */
    public function includeSession(Notification $notification)
    {
        $session = $notification->session;

        return $this->item($session, new SessionTransformer());
    }

    /**
     * @param Notification $notification
     *
     * @return \League\Fractal\Resource\Item
     */
    public function includeSessionUrl(Notification $notification)
    {
        $sessionUrl = $notification->sessionUrl;

        if ($sessionUrl) {
            return $this->item($sessionUrl, new SessionUrlTransformer());
        }
    }
}
