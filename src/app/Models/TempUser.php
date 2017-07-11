<?php

namespace Shrizzer\Models;

/**
 * Class TempUser
 *
 * This object is stored temporary inside the session.
 *
 * @package Shrizzer\Models
 */
class TempUser
{
    /**
     * @var array
     */
    protected $likes = [];

    /**
     * @var array
     */
    protected $dislikes = [];


    /**
     * @param $sessionUrlId
     */
    public function addLike($sessionUrlId)
    {
        $this->likes[$sessionUrlId] = true;

        $this->removeDislike($sessionUrlId);
    }

    /**
     * @param $sessionUrlId
     */
    public function removeLike($sessionUrlId)
    {
        unset($this->likes[$sessionUrlId]);
    }

    /**
     * @param $sessionUrlId
     *
     * @return bool
     */
    public function hasLiked($sessionUrlId)
    {
        return isset($this->likes[$sessionUrlId]);
    }

    /**
     * @param $sessionUrlId
     */
    public function addDislike($sessionUrlId)
    {
        $this->dislikes[$sessionUrlId] = true;

        $this->removeLike($sessionUrlId);
    }

    /**
     * @param $sessionUrlId
     */
    public function removeDislike($sessionUrlId)
    {
        unset($this->dislikes[$sessionUrlId]);
    }

    /**
     * @param $sessionUrlId
     *
     * @return bool
     */
    public function hasDisliked($sessionUrlId)
    {
        return isset($this->dislikes[$sessionUrlId]);
    }
}