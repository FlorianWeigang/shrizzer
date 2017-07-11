<?php

namespace Shrizzer\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SessionUrl
 *
 * @property int $vote_count
 */
class SessionUrl extends Model
{
    protected $table = 'session_url';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function url()
    {
        return $this->belongsTo(Url::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(SessionUrlComments::class, 'session_url_id');
    }
}