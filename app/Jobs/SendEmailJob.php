<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $mail;
    protected $recipient;
    protected $delaySeconds;

    /**
     * Create a new job instance.
     */
    public function __construct($recipient, $mail, $delaySeconds = 0)
    {
        $this->recipient = $recipient;
        $this->mail = $mail;
        $this->delaySeconds = $delaySeconds;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Add delay to avoid rate limiting
        if ($this->delaySeconds > 0) {
            sleep($this->delaySeconds);
        }
        
        Mail::to($this->recipient)->send($this->mail);
    }
}