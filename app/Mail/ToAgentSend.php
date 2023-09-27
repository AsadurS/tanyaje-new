<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ToAgentSend extends Mailable
{
    use Queueable, SerializesModels;
    public $data;
    public $isAgent;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data,$isAgent=false)
    {
        $this->data = $data;
        $this->isAgent = $isAgent;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->isAgent?'Sales Advisor Create': 'Renew Account')
            ->view('admin.mail.agent') // The email template view
            ->with(['agentOrAdvisor' => $this->data, 'isAgent'=> $this->isAgent]);
    }
}
