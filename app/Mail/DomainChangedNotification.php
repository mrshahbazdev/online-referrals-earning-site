<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DomainChangedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $oldDomain;
    public $newDomain;

    public function __construct($oldDomain, $newDomain)
    {
        $this->oldDomain = $oldDomain;
        $this->newDomain = $newDomain;
    }

    public function build()
    {
        return $this->subject('Website Domain Changed Alert')
                    ->html("<h1>Domain Change Alert</h1><p>The website domain has been changed from <strong>{$this->oldDomain}</strong> to <strong>{$this->newDomain}</strong>.</p>");
    }
}
