<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubjectMail extends Mailable
{
    use Queueable, SerializesModels;

    public int $subjectCount;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(int $subjectCount)
    {
        $this->subjectCount = $subjectCount;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Subjects from prize game')
            ->view('mail.subjects');
    }
}
