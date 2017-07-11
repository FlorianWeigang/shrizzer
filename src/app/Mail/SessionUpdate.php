<?php

namespace Shrizzer\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Shrizzer\Models\Session;

class SessionUpdate extends Mailable
{
    use Queueable, SerializesModels;

    public $notifications;

    public $session;

    /**
     * Create a new message instance.
     * @param $notifications
     * @param Session $session
     */
    public function __construct($notifications, Session $session)
    {
        $this->notifications = $notifications;
        $this->session = $session;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->subject('We got updates for you.');

        return $this->view('emails.send-session-notifications');
    }
}
