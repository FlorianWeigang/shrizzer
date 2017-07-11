<?php
/**
 * Created by PhpStorm.
 * User: florianweigang
 * Date: 11.04.17
 * Time: 11:31
 */

namespace Shrizzer\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Shrizzer\Exceptions\APIException;
use Shrizzer\Http\Controllers\Controller;
use Shrizzer\Models\Notification;
use Shrizzer\Repositories\NotificationRepository;
use Shrizzer\Repositories\SessionRepository;
use Shrizzer\Repositories\UserRepository;
use Shrizzer\Services\SessionService;

class UserController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @var SessionRepository
     */
    protected $sessionRepository;

    /**
     * @var SessionService
     */
    protected $sessionService;


    /**
     * @var NotificationRepository
     */
    protected $notificationRepository;


    /**
     * @param UserRepository $userRepository
     * @param SessionRepository $sessionRepository
     * @param SessionService $sessionService
     * @param NotificationRepository $notificationRepository
     */
    public function __construct(
        UserRepository $userRepository,
        SessionRepository $sessionRepository,
        SessionService $sessionService,
        NotificationRepository $notificationRepository
    ){
        $this->userRepository = $userRepository;
        $this->sessionRepository = $sessionRepository;
        $this->sessionService = $sessionService;
        $this->notificationRepository = $notificationRepository;
    }

    /**
     * @param $key
     * @return mixed
     *
     * @throws APIException
     */
    public function index($key)
    {
        $result = [];
        $session = $this->sessionRepository->getByKey($key);

        if (!$session) {
            throw new APIException('session not found', 404);
        }

        $urls = $session->getUsers();

        foreach ($urls as $url) {
            unset($url['email']);
            $result[] = $url;
        }

        return Response::json($result);
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

        $email = $request->get('email');

        if (!$email) {
            throw new APIException('email is required', 422);
        }

        $v = Validator::make(['email' => $request->get('email')], [
            'email' => 'required|email'
        ]);

        if ($v->fails()) {
            throw new APIException('the email is not valid', 422);
        }

        $user = $this->userRepository->findOrCreateByEmail($email);

        $this->sessionService->addUserToSession($session, $user);

        $this->notificationRepository->addNotification(Notification::SESSION_INVITED_TYPE, $session->id);

        return Response::json($user->email);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $key
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

        $user = $this->userRepository->findById($id);

        if (!$user) {
            throw new APIException('user not found', 404);
        }

        $this->sessionService->removeUserFromSession($session, $user);

        return Response::json(['status' => 'success']);
    }
}