<?php
namespace Shrizzer\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Request;
use Shrizzer\Helpers\NickNameGenerator;
use Shrizzer\Models\Notification;
use Shrizzer\Models\Session;
use Shrizzer\Repositories\NotificationRepository;
use Shrizzer\Repositories\SessionRepository;
use Shrizzer\Services\SessionService;

/**
 * Class IndexController
 *
 * @package Shrizzer\Http\Controllers
 */
class IndexController extends Controller
{
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
     * @param SessionRepository $sessionRepository
     * @param SessionService $sessionService
     * @param NotificationRepository $notificationRepository
     */
    public function __construct(
        SessionRepository $sessionRepository,
        SessionService $sessionService,
        NotificationRepository $notificationRepository
    ){
        $this->sessionRepository = $sessionRepository;
        $this->sessionService = $sessionService;
        $this->notificationRepository = $notificationRepository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $lastSeen = $this->sessionService->getLastSeenSessions();

        return view('index', [
            'lastSessions' => $lastSeen
        ]);
    }

    /**
     * @param $sessionKey
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function session($sessionKey)
    {
        $session = $this->sessionRepository->getByKey($sessionKey);
        $lastSeen = $this->sessionService->getLastSeenSessions();

        if (!$session) {
            abort(404, 'Session not found');
        }

        $this->sessionService->registerSeenSession($session);

        $sharedData['session'] = [
            'key' => $session->key,
            'name'  => $session->name,
            'description' => ($session->description) ? $session->description : ''
        ];


        return view('session', [
            'sharedData' => $sharedData,
            'session' => $session,
            'flashMessage' => session('session-flash'),
            'lastSessions' => $lastSeen
        ]);
    }

    /**
     * @param $sessionKey
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function sessionConfirm($sessionKey)
    {
        $session = $this->sessionRepository->getByKey($sessionKey);
        $vt = Request::get('vt');

        if (!$session) {
            abort(404, 'Session not found');
        }

        if (!$vt) {
            abort(404, 'parameters invalid');
        }

        if (!$this->sessionService->confirmInivtation($session, $vt)) {
            // redirect wrong

            session()->flash('session-flash', 'error-subscribed-to-session');

            return redirect('/session/' . $session->key);
        }

        session()->flash('session-flash', 'subscribed-to-session');

        $this->notificationRepository->addNotification(Notification::INVITATION_ACCEPTED_TYPE, $session->id);

        // redirect yes
        return redirect('/session/' . $session->key);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function about()
    {
        return view('imprint');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function contact()
    {
        return view('contact');
    }
}
