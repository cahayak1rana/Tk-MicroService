<?php

namespace App\Jobs;

use App\CustomMailer\CustomTransport;

class ProcessEmails extends Job
{

    public $mail_transport;
    public $request_type;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($customtransport, $request_type)
    {
        //
        $this->mail_transport = $customtransport;
        $this->request_type = $request_type;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $response = $this->mail_transport->processSend($this->request_type);
        
    }
}
