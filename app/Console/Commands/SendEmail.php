<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\CustomMailer\CustomTransport;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;

class SendEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:send 
                                {from-email : Email address of the sender} 
                                {from-name : The name of the sender} 
                                {subject}
                                {message} 
                                {mail-type} 
                                {driver-type}
                                {custom-id}
                                {send-to* : Array of recipients, use string format with pipe as separator e.g array("test@test.com|Testing user")} 
                                ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send transactional email to one or multiple recipients.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $config = Config::get('services.custom_mail', []);

        $transport = new CustomTransport($config['key'], $config['url']);

        /**
         * [
         *   'Email' => "gsk.player.12@gmail.com",
         *   'Name' => "Chaya"
        *  ]
         */
        // The to data here can be an array with a format 
        $to_data = $this->argument('send-to');
        if (!empty($to_data)) {
            foreach ($to_data as $key => $to) {
                $separate_email_name = explode('|', $to);
                if (isset($separate_email_name[1])) {
                    $transport->setTo($separate_email_name[0], $separate_email_name[1], 'array');
                }
                else {
                    $transport->setTo($separate_email_name[0], $separate_email_name[0], 'array');
                }
                
            }            
        }
        
        $transport->setFrom($this->argument('from-email'), ($this->argument('from-name') !== null ? $this->argument('from-name') : ''));
        $transport->setMessage($this->argument('message'));
        $transport->setSubject($this->argument('subject'));
        
        if ($this->argument('mail-type') == 'html') {
            $transport->setHtml();
        }

        // Extra parameters for API call. 
        $extra_parameters = array();
        // For mailjet.
        if ($this->argument('custom-id') != '') {
                $extra_parameters['CustomID'] = $this->argument('custom-id');
        }
        

        $response = $transport->send($this->argument('driver-type'));

        echo json_encode($response);
    }
}
