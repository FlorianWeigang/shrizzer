<?php

namespace Shrizzer\Repositories;

use Shrizzer\Models\SessionUrl;
use Shrizzer\Models\SessionUrlComments;

/**
 * Class CommentRepository
 *
 * @package Shrizzer\Repositories
 */
class CommentRepository
{
    /**
     * @param SessionUrl $sessionUrl
     *
     * @return \Shrizzer\Models\SessionUrlComments[]
     */
    public function fetchCommentsForSessionUrl(SessionUrl $sessionUrl)
    {
        return SessionUrlComments::where(['session_url_id' => $sessionUrl->id])
            ->get();
    }

    /**
     * @param SessionUrl $sessionUrl
     * @param $comment
     *
     * @return SessionUrlComments
     */
    public function addCommentToSessionUrl(SessionUrl $sessionUrl, $comment)
    {
        $sessionUrlComment = new SessionUrlComments();
        $sessionUrlComment->session_url_id = $sessionUrl->id;
        $sessionUrlComment->comment = $comment;
        $sessionUrlComment->save();

        return $sessionUrlComment;
    }

    /**
     * @param SessionUrl $sessionUrl
     * @param $commentId
     *
     * @return bool
     */
    public function deleteCommentBySessionUrlAndId(SessionUrl $sessionUrl, $commentId)
    {
        $sessionUrlComment = SessionUrlComments::where(['id' => $commentId])->first();

        if (!$sessionUrlComment || $sessionUrlComment->session_url_id != $sessionUrl->id) {
            return false;
        }

        $sessionUrlComment->delete();

        return true;
    }
}