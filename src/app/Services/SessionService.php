<?php

namespace Shrizzer\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Shrizzer\Helpers\NickNameGenerator;
use Shrizzer\Mail\SessionInvite;
use Shrizzer\Mail\SessionUpdate;
use Shrizzer\Models\Session;
use Shrizzer\Models\SessionUser;
use Shrizzer\Models\Url;
use Shrizzer\Models\User;
use Shrizzer\Repositories\SessionRepository;

/**
 * Class SessionService
 *
 * @package Shrizzer\Services
 */
class SessionService
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
     * @param Session $session
     * @param Url $url
     * @param array $data
     *
     * @return bool
     */
    public function addUrlToSession(Session $session, Url $url, array $data = [])
    {
        $session->urls()->detach([$url->id]);
        $session->urls()->attach($url->id, $data);

        $session->touch();

        return true;
    }

    /**
     * @param Session $session
     * @param Url $url
     */
    public function removeUrlFromSession(Session $session, Url $url)
    {
        $session->urls()->detach([$url->id]);
    }

    /**
     * @param Session $session
     * @param User $user
     *
     * @return bool
     */
    public function addUserToSession(Session $session, User $user)
    {
        $vt = uniqid();
        $session->users()->detach([$user->id]);
        $session->users()->attach($user->id, [
            'verification_token' => $vt,
            'nickname' => NickNameGenerator::generateNicknameByEmail($user->email)
        ]);

        /**
         * Write Mail to customer
         */
        Mail::to($user->email)
            ->send(new SessionInvite($session, $vt));

        return true;
    }

    /**
     * @param Session $session
     * @param $verificationToken
     *
     * @return bool
     */
    public function confirmInivtation(Session $session, $verificationToken)
    {
        $record = SessionUser::where([
            'session_id' => $session->id,
            'verification_token' => $verificationToken
        ])->first();

        if (!$record || $record->status === 'confirmed') {
            return false;
        }

        $record->status = 'confirmed';
        $record->save();

        return true;
    }

    /**
     * @param Session $session
     * @param User $user
     */
    public function removeUserFromSession(Session $session, User $user)
    {
        $session->users()->detach([$user->id]);
    }

    /**
     * @todo could be moved to temp user
     *
     * @param Session $session
     */
    public function registerSeenSession(Session $session)
    {
        $lastSeen = session('lastSessions', []);

        if (array_search($session->id, $lastSeen) > -1) {
            return;
        }
        $lastSeen = array_unique($lastSeen);
        $lastSeen[] = $session->id;

        if (count($lastSeen) > 15) {
            $lastSeen = array_slice($lastSeen, 1);
        }

        session(['lastSessions' => $lastSeen]);
    }

    /**
     * @todo could be moved to temp user
     *
     * @return Session[]
     */
    public function getLastSeenSessions()
    {
        $result = [];

        $lastSeen = array_reverse(session('lastSessions', []));

        foreach ($lastSeen as $index => $id) {
            $session = Session::find($id);

            if ($session) {
                $result[] = $session;
            } else {
                unset($lastSeen[$index]);
                session(['lastSessions' => $lastSeen]);
            }
        }

        return $result;
    }

    /**
     *
     */
    public function notifyUserAboutSessionChange()
    {
        $sessions = $this->sessionRepository->getAllSessionsToNotify();

        foreach ($sessions as $session) {
            if (is_null($session->last_notified_at)) {
                // this means that a notification was never send to the user.
                // for the first time we set this to the date right now because we know that every
                // subscriber get an initial notification

                $session->last_notified_at = new Carbon();
                $session->save();
            } else {
                $notifications = $session->getGroupedNotificationsNewerThen(new Carbon($session->last_notified_at));
                $users = $session->getConfirmedUsers();

                foreach ($users as $user) {
                    try {
                        Mail::to($user->email)
                            ->send(new SessionUpdate($notifications, $session));

                        Log::info('SessionService: send out email notification', [
                            'sessionID' => $session->id,
                            'user' => $user->email,
                            'notificationCount' => count($notifications)
                        ]);
                    } catch (\Exception $e) {
                        Log::error('SessionService: error during notification delivery', [
                            'sessionID' => $session->id,
                            'user' => $user->email,
                            'exceptionMessage' => $e->getMessage()
                        ]);
                    }
                }

                $session->last_notified_at = new Carbon();
                $session->save();
            }
        }
    }
}