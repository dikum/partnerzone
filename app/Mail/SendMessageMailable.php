<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendMessageMailable extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $content;
    public $messageSubject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, $content)
    {
        $this->content = $content;
        $this->messageSubject = $subject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        Log::debug($this->content);
        return 
            $this->subject($this->messageSubject)
            ->view('email.email', ['content' => $this->content])->render();
    }
}
