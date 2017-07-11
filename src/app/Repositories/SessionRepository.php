<?php

namespace Shrizzer\Repositories;

use Illuminate\Support\Facades\DB;
use Shrizzer\Models\Session;
use Shrizzer\Models\SessionUrl;

/**
 * Class SessionRepository
 *
 * @package Shrizzer\Repositories
 */
class SessionRepository
{
    /**
     * @param int $id
     *
     * @return Session
     */
    public function getById($id)
    {
        return Session::find($id)->first;
    }

    /**
     * @param string $key
     *
     * @return Session
     */
    public function getByKey($key)
    {
        return Session::where(['key' => $key])
            ->first();
    }

    /**
     * @param $key
     *
     * @return bool
     */
    public function deleteByKey($key)
    {
        $session = $this->getByKey($key);

        if (!$session) {
            return false;
        }

        return $session->delete();
    }

    /**
     * Gets all sessions which got newer notifications then last_notified_at on session entry or
     * last_notified_at is null
     *
     * @return Session[]
     */
    public function getAllSessionsToNotify()
    {
        $ids = [];
        $rawIds = DB::select(DB::raw("
                select distinct s.id from sessions s
                left join notifications n on s.id = n.session_id
                where n.id is not null and (s.last_notified_at < n.created_at or s.last_notified_at is null)
        "));

        foreach ($rawIds as $id) {
            $ids[] = $id->id;
        }

        if (!$ids) {
            return $ids;
        }

        return Session::whereIn('id', $ids)->get();
    }

    /**
     * @return Session[]
     */
    public function findAll()
    {
        return Session::all();
    }

    /**
     * @param Session $session
     *
     * @return bool
     */
    public function save(Session $session)
    {
        if (is_null($session->key)) {
            $session->key = uniqid();
        }

        return $session->save();
    }

    /**
     * @param SessionUrl $sessionUrl
     *
     * @return int
     */
    public function upVoteSessionUrl(SessionUrl $sessionUrl)
    {
        return $sessionUrl->increment('vote_count');
    }

    /**
     * @param SessionUrl $sessionUrl
     *
     * @return int
     */
    public function downVoteSessionUrl(SessionUrl $sessionUrl)
    {
        return $sessionUrl->decrement('vote_count');
    }
}