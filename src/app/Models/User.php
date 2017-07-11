<?php

namespace Shrizzer\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class User
 *
 * @property int $id
 * @property string $email
 * @property string $short_name
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class User extends Model
{
    protected $guarded = [];
}