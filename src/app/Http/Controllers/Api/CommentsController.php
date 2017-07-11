<?php

namespace Shrizzer\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Shrizzer\Exceptions\APIException;
use Shrizzer\Http\Controllers\Controller;
use Shrizzer\Models\Notification;
use Shrizzer\Repositories\CommentRepository;
use Shrizzer\Repositories\NotificationRepository;
use Shrizzer\Repositories\SessionRepository;

/**
 * Class CommentsController
 *
 * @package Shrizzer\Http\Controllers\Api
 */
class CommentsController extends Controller
{
    /**
     * @var SessionRepository
     */
    protected $sessionRepository;

    /**
     * @var CommentRepository
     */
    protected $commentRepository;

    /**
     * @var NotificationRepository
     */
    protected $notificationRepository;

    /**
     * @param SessionRepository $sessionRepository
     * @param CommentRepository $commentRepository
     * @param NotificationRepository $notificationRepository
     */
    public function __construct(
        SessionRepository $sessionRepository,
        CommentRepository $commentRepository,
        NotificationRepository $notificationRepository
    ) {
        $this->sessionRepository = $sessionRepository;
        $this->commentRepository = $commentRepository;
        $this->notificationRepository = $notificationRepository;
    }

    /**
     * @param $sessionKey
     * @param $urlId
     *
     * @throws APIException
     */
    public function index($sessionKey, $urlId)
    {
        $session = $this->sessionRepository->getByKey($sessionKey);

        if (!$session) {
            throw new APIException('session not found', 404);
        }

        $sessionUrl = $session->getSessionUrlPivotByUrlId($urlId);

        if (!$sessionUrl) {
            throw new APIException('url not found', 404);
        }

        $comments = $this->commentRepository->fetchCommentsForSessionUrl($sessionUrl);

        return Response::json($comments);
    }

    /**
     * @param Request $request
     * @param $sessionKey
     * @param $urlId
     *
     * @return
     *
     * @throws APIException
     */
    public function store(Request $request, $sessionKey, $urlId)
    {
        $session = $this->sessionRepository->getByKey($sessionKey);
        $comment = $request->get('comment');

        if (!$session) {
            throw new APIException('session not found', 404);
        }

        if (!$comment) {
            throw new APIException('a comment must be present', 422);
        }

        $sessionUrl = $session->getSessionUrlPivotByUrlId($urlId);

        if (!$sessionUrl) {
            throw new APIException('url not found', 404);
        }

        $comment = $this->commentRepository->addCommentToSessionUrl($sessionUrl, $comment);

        $this->notificationRepository->addNotification(Notification::POST_COMMENTED_TYPE, $session->id, $urlId);

        return Response::json($comment);
    }

    /**
     * @param $sessionKey
     * @param $urlId
     * @param $id
     *
     * @return mixed
     *
     * @throws APIException
     */
    public function destroy($sessionKey, $urlId, $id)
    {
        $session = $this->sessionRepository->getByKey($sessionKey);

        if (!$session) {
            throw new APIException('session not found', 404);
        }

        $sessionUrl = $session->getSessionUrlPivotByUrlId($urlId);

        if (!$sessionUrl) {
            throw new APIException('url not found', 404);
        }

        $success = $this->commentRepository->deleteCommentBySessionUrlAndId($sessionUrl, $id);

        return Response::json(['success' => $success]);
    }
}