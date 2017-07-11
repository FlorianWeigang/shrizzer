<?php

namespace Shrizzer\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Notifications
 *
 * @property int $id
 * @property string $type
 * @property int $session_id
 * @property int $url_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Notification extends Model
{
    const POST_CREATED_TYPE = 'post_created';
    const POST_LIKED_TYPE = 'post_liked';
    const POST_DISLIKED_TYPE = 'post_disliked';
    const POST_COMMENTED_TYPE = 'post_commented';

    const SESSION_INVITED_TYPE = 'session_invited';
    const INVITATION_ACCEPTED_TYPE = 'invitation_accepted';

    const NOTIFICATION_GROUP_POSTS = 'posts';
    const NOTIFICATION_GROUP_ACTIVITY = 'post_activity';
    const NOTIFICATION_GROUP_SESSION = 'session';

    const GROUPS = [
        self::POST_CREATED_TYPE => self::NOTIFICATION_GROUP_POSTS,
        self::POST_LIKED_TYPE => self::NOTIFICATION_GROUP_ACTIVITY,
        self::POST_DISLIKED_TYPE => self::NOTIFICATION_GROUP_ACTIVITY,
        self::POST_COMMENTED_TYPE => self::NOTIFICATION_GROUP_ACTIVITY,
        self::SESSION_INVITED_TYPE => self::NOTIFICATION_GROUP_SESSION,
        self::INVITATION_ACCEPTED_TYPE => self::NOTIFICATION_GROUP_SESSION
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function session()
    {
        return $this->belongsTo(Session::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function url()
    {
        return $this->belongsTo(Url::class);
    }

    /**
     * @return mixed
     */
    public function sessionUrl()
    {
        return $this->hasOne(SessionUrl::class, 'session_id', 'session_id')->where('url_id', $this->url_id);
    }
}