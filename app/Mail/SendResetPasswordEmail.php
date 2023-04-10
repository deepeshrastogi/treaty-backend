<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendResetPasswordEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // reset password link
        $template = $this->details['template'];
        if($template=="create_password"){
             
            return $this->subject('Willkommen im neuen Treaty Kundenportal')->view('emails.auth.'.$this->details['template'])->with('data', $this->details);

        }else{
        
            return $this->subject('Passwort zurücksetzen E-Mail Treaty')->view('emails.auth.'.$this->details['template'])->with('data', $this->details);

        }
        die;
    }
}
