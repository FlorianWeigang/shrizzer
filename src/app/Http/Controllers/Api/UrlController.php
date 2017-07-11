<?php

namespace Shrizzer\Http\Controllers\Api;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Shrizzer\Exceptions\APIException;
use Shrizzer\Http\Controllers\Controller;
use Shrizzer\Models\Notification;
use Shrizzer\Repositories\CommentRepository;
use Shrizzer\Repositories\NotificationRepository;
use Shrizzer\Repositories\SessionRepository;
use Shrizzer\Repositories\UrlRepository;
use Shrizzer\Repositories\UserRepository;
use Shrizzer\Services\SessionService;

class UrlController extends Controller
{
    /**
     * @var SessionRepository
     */
    private $sessionRepository;

    /**
     * @var UrlRepository
     */
    private $urlRepository;

    /**
     * @var SessionService
     */
    private $sessionService;

    /**
     * @var CommentRepository
     */
    private $commentRepository;

    /**
     * @var NotificationRepository
     */
    private $notificationRepository;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @param SessionService $sessionService
     * @param SessionRepository $sessionRepository
     * @param UrlRepository $urlRepository
     * @param CommentRepository $commentRepository
     * @param NotificationRepository $notificationRepository
     * @param UserRepository $userRepository
     */
    public function __construct(
        SessionService $sessionService,
        SessionRepository $sessionRepository,
        UrlRepository $urlRepository,
        CommentRepository $commentRepository,
        NotificationRepository $notificationRepository,
        UserRepository $userRepository
    ) {
        $this->sessionRepository = $sessionRepository;
        $this->urlRepository = $urlRepository;
        $this->sessionService = $sessionService;
        $this->commentRepository = $commentRepository;
        $this->notificationRepository = $notificationRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param $key
     *
     * @return \Illuminate\Http\Response
     *
     * @throws APIException
     */
    public function index($key)
    {
        $session = $this->sessionRepository->getByKey($key);
        $user = $this->userRepository->getActiveUser();

        if (!$session) {
            throw new APIException('session not found', 404);
        }

        $urls = $session->getUrls();

        foreach ($urls as $url) {
            $sessionUrl = $session->getSessionUrlPivotByUrlId($url->id);
            $url->comments = $this->commentRepository->fetchCommentsForSessionUrl($sessionUrl);
            $url->hasUpvoted = $user->hasLiked($sessionUrl->id);
            $url->hasDownvoted = $user->hasDisliked($sessionUrl->id);
        }

        return Response::json($urls);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param $key
     *
     * @return \Illuminate\Http\Response
     *
     * @throws APIException
     */
    public function store(Request $request, $key)
    {
        $session = $this->sessionRepository->getByKey($key);

        if (!$session) {
            throw new APIException('session not found', 404);
        }

        $urlParam       = $request->get('url');
        $title          = $request->get('title');
        $description    = $request->get('description');

        if (!$urlParam) {
            throw new APIException('url is required', 422);
        }

        $v = Validator::make(['url' => $request->get('url')], [
            'url' => 'required|url'
        ]);

        if ($v->fails()) {
            throw new APIException('the url is not valid', 422);
        }

        $url = $this->urlRepository->findOrCreateByUrl($urlParam);
        $data = ['title' => $title, 'description' => $description];

        if (!$this->sessionService->addUrlToSession($session, $url, $data)) {
            throw new APIException('url couldnt be added to the session', 409);
        }

        $this->notificationRepository->addNotification(Notification::POST_CREATED_TYPE, $session->id, $url->id);

        return Response::json($url);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param $sessionKey
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     *
     * @throws APIException
     */
    public function update(Request $request, $sessionKey, $id)
    {
        $vote = $request->get('vote');
        $user = $this->userRepository->getActiveUser();

        if (!$vote || !($vote == "1" || $vote == "-1")) {
            throw new APIException('vote is required', 422);
        }

        $session = $this->sessionRepository->getByKey($sessionKey);

        if (!$session) {
            throw new APIException('session not found', 404);
        }

        $sessionUrl = $session->getSessionUrlPivotByUrlId($id);

        if (!$sessionUrl) {
            throw new APIException('url not found', 404);
        }

        if ($vote > 0) {

            if ($user->hasLiked($sessionUrl->id)) {
                throw new APIException('you can not vote twice', 422);
            }

            $this->sessionRepository->upVoteSessionUrl($sessionUrl);

            $this->notificationRepository->addNotification(Notification::POST_LIKED_TYPE, $session->id, $id);
            $user->addLike($sessionUrl->id);
        } else {

            if ($user->hasDisliked($sessionUrl->id)) {
                throw new APIException('you can not vote twice', 422);
            }

            $this->sessionRepository->downVoteSessionUrl($sessionUrl);

            $this->notificationRepository->addNotification(Notification::POST_DISLIKED_TYPE, $session->id, $id);
            $user->addDislike($sessionUrl->id);
        }

        $sessionUrl->fresh();


        $this->userRepository->saveTempUser($user);

        return Response::json(['newCount' => $sessionUrl->vote_count]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     *
     * @throws APIException
     */
    public function destroy($key, $id)
    {
        $session = $this->sessionRepository->getByKey($key);

        if (!$session) {
            throw new APIException('session not found', 404);
        }

        $url = $this->urlRepository->getUrlById($id);

        if (!$url) {
            throw new APIException('url not found', 404);
        }

        $this->sessionService->removeUrlFromSession($session, $url);

        return Response::json(['status' => 'success']);
    }
}
