<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Jenssegers\Agent\Agent;

class EmailNotFound extends Mailable
{
    use Queueable;
    use SerializesModels;
    protected $agent;
    public $user;
    public $platform;
    public $browser;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, Agent $agent)
    {
        $this->user = $user;
        $this->agent = $agent;

        if ($this->agent->platform() != '') {
            $this->platform = $this->agent->platform();
        } else {
            $this->platform = 'Unknown';
        }

        if ($this->agent->browser() != '') {
            $this->browser = $this->agent->browser();
        } else {
            $this->browser = 'Unknown';
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.email-not-found')
                    ->subject('Reset K-Net password');
    }
}
