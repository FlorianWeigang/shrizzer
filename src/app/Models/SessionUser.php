<?php

namespace Shrizzer\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SessionUser
 *
 * @property int $id
 * @property int $session_id
 * @property int $user_id
 * @property string $status
 * @property string $verification_token
 * @property string $nickname
 */
class SessionUser extends Model
{
    public $table = 'session_user';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}