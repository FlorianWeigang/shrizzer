<?php

namespace Shrizzer\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Session
 *
 * @property int $id
 * @property string $key
 * @property string $name
 * @property string $description
 * @property Carbon $last_url_added_at
 * @property Carbon $last_notified_at
 * @property Carbon $created_ad
 * @property Carbon $updated_at
 */
class Session extends Model
{
    /**
 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
 */
    public function urls()
    {
        return $this->belongsToMany(Url::class)->withTimestamps();;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * @return mixed
     */
    public function getNotifications()
    {
        return $this->notifications()->orderBy('id', 'desc')->get();
    }

    /**
     * @return array
     */
    public function getGroupedNotifications()
    {
        $result = [];
        $notifications = $this->getNotifications();

        foreach ($notifications as $notification) {
            $types = Notification::GROUPS;
            $type = $types[$notification->type];

            $result[$type][] = $notification;
        }

        return $result;
    }

    /**
     * @return Url[]
     */
    public function getUrls()
    {
        return $this->urls()->withPivot(['title', 'vote_count'])->orderBy('id', 'desc')->get();
    }

    /**
     * @return mixed
     */
    public function getLastUrl()
    {
        return $this->urls()->orderBy('id', 'desc')->first();
    }

    /**
     * @param Carbon $date
     *
     * @return int
     */
    public function getUrlsNewerThen(Carbon $date)
    {
        return $this->urls()->where('session_url.created_at', '>', $date)->get();
    }

    /**
     * @param Carbon $date
     *
     * @return array
     */
    public function getGroupedNotificationsNewerThen(Carbon $date)
    {
        $result = [];
        $notifications = $this->notifications()
            ->where('created_at', '>', $date)
            ->orderBy('id', 'desc')
            ->get();

        foreach ($notifications as $notification) {
            $types = Notification::GROUPS;
            $type = $types[$notification->type];

            $result[$type][] = $notification;
        }

        return $result;
    }

    /**
     * @return Url[]
     */
    public function getUsers()
    {
        return $this->users()->withPivot('status', 'nickname')->get();
    }

    /**
     * @return User[]
     */
    public function getConfirmedUsers()
    {
        return $this->users()->where('status', '=', 'confirmed')->get();
    }

    /**
     * @param $urlId
     */
    public function getSessionUrlPivotByUrlId($urlId)
    {
        return SessionUrl::where([
            'session_id' => $this->id,
            'url_id' => $urlId
        ])->first();
    }
}
