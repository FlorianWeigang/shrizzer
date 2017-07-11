<?php

namespace Shrizzer\Http\Controllers\Api;

use Illuminate\Support\Facades\Response;
use Shrizzer\Exceptions\APIException;
use Shrizzer\Repositories\SessionRepository;
use Shrizzer\Transformers\NotificationTransformer;

/**
 * Class NotificationController
 *
 * @package Shrizzer\Http\Controllers\Api
 */
class NotificationController
{
    /**
     * @var SessionRepository
     */
    protected $sessionRepository;


    /**
     * @param SessionRepository $sessionRepository
     */
    public function __construct(SessionRepository $sessionRepository)
    {
        $this->sessionRepository = $sessionRepository;
    }

    /**
     * @param $sessionKey
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws APIException
     */
    public function index($sessionKey)
    {
        if (!$sessionKey) {
            throw new APIException('session key required', 422);
        }

        $session = $this->sessionRepository->getByKey($sessionKey);

        if (!$session) {
            throw new APIException('session not found', 404);
        }

        $notifications = $session->getNotifications();

        return fractal($notifications, new NotificationTransformer())->respond();
    }
}