<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CustomMailer\CustomTransport;
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
         *   'Email' => "gsk.player.12@gmail.com",
         *   'Name' => "Chaya"
        *  ]
         */
        $to_data = $request->get('send_to');
        if (!empty($to_data)) {
            foreach ($to_data as $key => $to) {
                $transport->setTo($to['send_to_email'], $to['send_to_name'], 'array');
            }            
        }
        
        $transport->setFrom($request->get('from_email'), ($request->get('from_name') !== null ? $request->get('from_name') : ''));
        $transport->setMessage($request->get('message'));
        $transport->setSubject($request->get('subject'));
        
        if ($request->get('html') == true) {
            $transport->setHtml();
        }

        // Extra parameters for API call. 
        $extra_parameters = array();
        // For mailjet.
        if ($request->customID != '') {
            $extra_parameters['CustomID'] = $request->get('custom_id');
        }

        $response = $transport->send($request->get('mail_type'));

        echo json_encode($response);
    }
}
