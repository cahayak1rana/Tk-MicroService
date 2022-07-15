<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CustomMailer\CustomTransport;
use App\Jobs\ProcessEmails;
use Illuminate\Support\Facades\Config;

class SendMailController extends Controller
{
    //
    public function sendMail(Request $request) {
        // $param['email_type'] = 'smtp';
        // $params['data']           = ['foo' => 'Hello John Doe!']; //optional
        // $params['to']             = 'charles@hariyadi.net'; //required
        // $params['template_type']  = 'markdown';  //default is view
        // $params['template']       = 'emails.app-mailer'; //path to the email template
        // $params['subject']        = 'Some Awesome Subject'; //optional
        // $params['from_email']     = 'jondoe@example.com';
        // $params['from_name']      = 'John Doe';
 
        $config = Config::get('services.custom_mail', []);

        $transport = new CustomTransport($config['key'], $config['url']);

        /**
         * [
         *   'Email' => "asdf@gmail.com",
         *   'Name' => "Chaya"
        *  ]
         */
        $to_data = $request->get('send_to');
        if (!empty($to_data)) {
            foreach ($to_data as $key => $to) {
                $transport->setTo($to['send_to_email'], $to['send_to_name'], 'array');
            }            
        }
        
        // Process from, message and subject data. 
        $transport->setFrom($request->get('from_email'), ($request->get('from_name') !== null ? $request->get('from_name') : ''));
        $transport->setMessage($request->get('message'));
        $transport->setSubject($request->get('subject'));
        
        // Check if user can set html. 
        if ($request->get('mail_type') == 'html') {
            $transport->setHtml();
        }

        // Extra parameters for API call. 
        $extra_parameters = array();
        // For mailjet.
        if ($request->customID != '') {
            $extra_parameters['CustomID'] = $request->get('custom_id');
        }

        // Check whether task will be queued. 
        $queue = $request->get('queue');
        if ($queue == 'true') {
            $transportJobs = new ProcessEmails($transport, $request->get('driver_type'));
            $this->dispatch($transportJobs);
        }
        else {
            $response = $transport->processSend($request->get('driver_type'));  
            echo json_encode(array('success' =>$response));
        }
        
    }
}
