<?php

namespace Shrizzer\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Url
 *
 * @property string $url_identifier
 * @property string $url
 * @property string $favicon_url
 * @property string $base_url
 * @property string $image_url
 * @property string $title
 * @property string $descriptions
 * @property string $open_graph
 * @property string $embed
 * @property string $type
 * @property bool   $valid
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package Shrizzer\Models
 */
class Url extends Model
{
    protected $guarded = [];
}