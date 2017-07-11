<?php

namespace Shrizzer\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Shrizzer\Models\Session;

class SessionInvite extends Mailable
{
    use Queueable, SerializesModels;

    public $session;
    public $vt;

    /**
     * Create a new message instance.
     *
     * @param Session $session
     * @param $verificationToken
     */
    public function __construct(Session $session, $verificationToken)
    {
        $this->session = $session;
        $this->vt = $verificationToken;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->subject('You are invited to a session on shrizzer.com');

        return $this->view('emails.session-invite');
    }
}
